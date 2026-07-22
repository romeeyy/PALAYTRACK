<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Client;
use App\Models\RiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class DeliveryController extends Controller
{
    public function index()
    {
        return Delivery::with('riceType')->latest()->get();
    }

    public function store(Request $request)
    {
        logger('DELIVERY STORE AUTH CHECK', [
    'auth_id' => Auth::id(),
    'auth_user' => Auth::user(),
    'session_id' => session()->getId(),
]);
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'rice_type_id' => 'required|exists:rice_types,id',
            'sacks' => 'required|numeric|min:0.5|multiple_of:0.5',
            'palay_weight' => 'required|numeric|min:1',
            'notes' => 'nullable|string'
        ]);

        $riceType = RiceType::findOrFail($validated['rice_type_id']);

        $recoveryRate = $riceType->recovery_rate;
        $estimatedRice = $validated['palay_weight'] * ($recoveryRate / 100);
        $queueNumber = (Delivery::max('queue_number') ?? 0) + 1;

        $delivery = DB::transaction(function () use ($validated, $recoveryRate, $estimatedRice, $queueNumber) {
            $client = Client::firstOrCreate(
                ['contact_number' => $validated['contact_number']],
                [
                    'name' => $validated['client_name'],
                    'client_type' => 'regular',
                ]
            );

            if ($client->name !== $validated['client_name']) {
                $client->update(['name' => $validated['client_name']]);
            }

            $delivery = Delivery::create([
            'staff_id' => Auth::id(),
            'delivery_id' => 'DEL-' . strtoupper(Str::random(6)),
            'queue_number' => $queueNumber,
            'client_id' => $client->id,
            'client_name' => $validated['client_name'],
            'contact_number' => $validated['contact_number'],
            'rice_type_id' => $validated['rice_type_id'],
            'sacks' => $validated['sacks'],
            'palay_weight' => $validated['palay_weight'],
            'recovery_rate' => $recoveryRate,
            'estimated_rice' => $estimatedRice,
            'actual_rice' => null,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
            'delivered_at' => now()
            ]);

            $delivery->inventoryLogs()->create([
                'stock_category' => 'palay',
                'type' => 'in',
                'quantity' => $delivery->palay_weight,
                'remarks' => 'Palay added from recorded delivery',
                'logged_at' => now(),
            ]);

            return $delivery;
        });

        return response()->json([
            'message' => 'Delivery recorded successfully',
            'data' => $delivery->load('riceType')
        ], 201);
    }

    public function show(Delivery $delivery)
    {
        return $delivery->load('riceType');
    }

    public function update(Request $request, Delivery $delivery)
    {
        $validated = $request->validate([
            'client_name' => 'sometimes|required|string|max:255',
            'contact_number' => 'sometimes|required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $delivery->update($validated);

        return response()->json([
            'message' => 'Delivery updated successfully',
            'data' => $delivery
        ]);
    }

    public function destroy(Delivery $delivery)
    {
        if ($delivery->inventoryLogs()->exists()) {
            throw ValidationException::withMessages([
                'delivery' => 'A delivery with inventory history cannot be deleted.',
            ]);
        }

        $delivery->delete();

        return response()->json([
            'message' => 'Delivery deleted successfully'
        ]);
    }
}
