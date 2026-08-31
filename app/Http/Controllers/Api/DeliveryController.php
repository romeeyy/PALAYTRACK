<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Services\DeliveryRecordingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class DeliveryController extends Controller
{
    public function index()
    {
        return Delivery::with('riceType')->latest()->get();
    }

    public function store(Request $request, DeliveryRecordingService $recordingService)
    {
        abort_unless(Auth::check() && in_array(Auth::user()->role, ['owner', 'staff'], true), 403);

        $validated = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'regex:/^(09|\+639|639)\d{9}$/'],
            'rice_type_id' => ['required', 'exists:rice_types,id'],
            'sacks' => ['required', 'numeric', 'min:0.5', 'multiple_of:0.5'],
            'palay_weight' => ['required', 'numeric', 'min:1'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'contact_number.regex' => 'Enter a valid Philippine mobile number (e.g., 09123456789).',
            'sacks.multiple_of' => 'Number of sacks must be a whole or half sack (example: 2 or 2.5).',
            'sacks.min' => 'Number of sacks must be at least half a sack (0.5).',
        ]);

        $delivery = $recordingService->record($validated, (int) Auth::id());

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
