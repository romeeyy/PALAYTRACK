<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\StaffAccountHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StaffAccountController extends Controller
{
    private function ownerOnly(): void
    {
        abort_unless(Auth::check() && Auth::user()->role === 'owner', 403);
    }

    public function index(Request $request)
    {
        $this->ownerOnly();
        $validated = $request->validate([
            'search' => 'nullable|string|max:100',
            'status' => 'nullable|in:all,active,inactive',
        ]);

        $search = trim($validated['search'] ?? '');
        $status = $validated['status'] ?? 'all';
        $query = User::query()->where('role', 'staff');

        if ($search !== '') {
            $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }
        if ($status !== 'all') {
            $query->where('is_active', $status === 'active');
        }

        $staffAccounts = $query->latest()->paginate(10)->withQueryString();

        return view('owner.staff-accounts', compact('staffAccounts', 'search', 'status'));
    }

    public function create()
    {
        $this->ownerOnly();
        return view('owner.create-staff');
    }

    public function store(Request $request)
    {
        $this->ownerOnly();
        $request->merge([
            'name' => preg_replace('/\s+/', ' ', trim((string) $request->name)),
            'email' => strtolower(trim((string) $request->email)),
        ]);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        DB::transaction(function () use ($validated): void {
            $staff = User::create($validated + ['role' => 'staff', 'is_active' => true]);
            $this->log($staff, 'created', 'account', null, 'Active staff account');
        });

        return redirect()->route('owner.staff-accounts')->with('success', 'Staff account created successfully.');
    }

    public function edit(int $id)
    {
        $this->ownerOnly();
        $staff = $this->staff($id);
        return view('owner.edit-staff', compact('staff'));
    }

    public function update(Request $request, int $id)
    {
        $this->ownerOnly();
        $staff = $this->staff($id);
        $request->merge([
            'name' => preg_replace('/\s+/', ' ', trim((string) $request->name)),
            'email' => strtolower(trim((string) $request->email)),
        ]);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($staff->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        DB::transaction(function () use ($staff, $validated): void {
            foreach (['name', 'email'] as $field) {
                if ($staff->{$field} !== $validated[$field]) {
                    $this->log($staff, 'updated', $field, $staff->{$field}, $validated[$field]);
                    $staff->{$field} = $validated[$field];
                }
            }
            if (! empty($validated['password'])) {
                $staff->password = $validated['password'];
                $this->log($staff, 'updated', 'password', null, 'Password updated');
            }
            $staff->save();
        });

        return redirect()->route('owner.staff-accounts')->with('success', 'Staff account updated successfully.');
    }

    public function toggleStatus(int $id)
    {
        $this->ownerOnly();
        $staff = $this->staff($id);
        $old = $staff->is_active ? 'Active' : 'Inactive';
        $staff->is_active = ! $staff->is_active;
        $staff->save();
        $this->log($staff, 'status_changed', 'status', $old, $staff->is_active ? 'Active' : 'Inactive');

        return redirect()->route('owner.staff-accounts')->with('success', 'Staff account status updated successfully.');
    }

    public function history(Request $request)
    {
        $this->ownerOnly();
        $histories = StaffAccountHistory::with(['staff', 'owner'])
            ->latest('changed_at')->latest('id')->paginate(15)->withQueryString();
        return view('owner.staff-account-history', compact('histories'));
    }

    private function staff(int $id): User
    {
        return User::where('role', 'staff')->findOrFail($id);
    }

    private function log(User $staff, string $action, ?string $field, ?string $old, ?string $new): void
    {
        StaffAccountHistory::create([
            'staff_id' => $staff->id,
            'owner_id' => Auth::id(),
            'action' => $action,
            'field' => $field,
            'old_value' => $old,
            'new_value' => $new,
            'changed_at' => now(),
        ]);
    }
}
