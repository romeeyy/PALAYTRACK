<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientHistory;
use App\Services\ClientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'client_type' => ['nullable', 'in:all,regular,commercial'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $type = $validated['client_type'] ?? 'all';
        $search = trim($validated['search'] ?? '');

        $query = Client::query()->latest();

        if ($type !== 'all') {
            $query->where('client_type', $type);
        }

        if (!empty($search)) {
            $normalizedSearch = mb_strtolower($search);

            $query->where(function ($q) use ($normalizedSearch) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$normalizedSearch}%"])
                  ->orWhere('contact_number', 'like', "%{$normalizedSearch}%");
            });
        }

        $clients = $query->paginate(10)->withQueryString();

        return view('owner.clients', compact('clients', 'type', 'search'));
    }

    public function updateType(Request $request, Client $client)
    {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $request->validate([
            'client_type' => 'required|in:regular,commercial',
        ]);

        if ($client->client_type !== $request->client_type) {
            DB::transaction(function () use ($client, $request): void {
                $this->log($client, 'client_type', $client->client_type, $request->client_type);
                $client->update(['client_type' => $request->client_type]);
            });
        }

        return back()->with('success', 'Client classification updated successfully.');
    }

    public function update(Request $request, Client $client, ClientService $service)
    {
        $this->ownerOnly();
        $request->merge([
            'name' => $service->normalizeName((string) $request->name),
            'contact_number' => $service->normalizeContact((string) $request->contact_number),
        ]);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'regex:/^09\d{9}$/'],
        ]);

        $duplicate = Client::where('name', $validated['name'])
            ->where('contact_number', $validated['contact_number'])
            ->whereKeyNot($client->id)->exists();
        if ($duplicate) {
            return back()->withErrors(['contact_number' => 'A client with the same name and contact number already exists.'])->withInput();
        }

        DB::transaction(function () use ($client, $validated): void {
            foreach (['name', 'contact_number'] as $field) {
                if ($client->{$field} !== $validated[$field]) {
                    $this->log($client, $field, $client->{$field}, $validated[$field]);
                    $client->{$field} = $validated[$field];
                }
            }
            $client->save();
        });

        return redirect()->route('owner.clients')->with('success', 'Client profile updated successfully. Existing delivery snapshots were preserved.');
    }

    public function history()
    {
        $this->ownerOnly();
        $histories = ClientHistory::with(['client', 'owner'])->latest('changed_at')->latest('id')->paginate(15);
        return view('owner.client-history', compact('histories'));
    }

    private function ownerOnly(): void
    {
        abort_unless(Auth::check() && Auth::user()->role === 'owner', 403);
    }

    private function log(Client $client, string $field, ?string $old, ?string $new): void
    {
        ClientHistory::create([
            'client_id' => $client->id, 'owner_id' => Auth::id(), 'field' => $field,
            'old_value' => $old, 'new_value' => $new, 'changed_at' => now(),
        ]);
    }
}
