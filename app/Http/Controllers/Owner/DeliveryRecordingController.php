<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Services\DeliveryRecordingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryRecordingController extends Controller
{
    public function store(Request $request, DeliveryRecordingService $service)
    {
        abort_unless(Auth::check() && Auth::user()->role === 'owner', 403);
        $validated = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'regex:/^(09|\+639|639)\d{9}$/'],
            'rice_type_id' => ['required', 'exists:rice_types,id'],
            'sacks' => ['required', 'numeric', 'min:0.5', 'multiple_of:0.5'],
            'palay_weight' => ['required', 'numeric', 'min:1'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'sacks.multiple_of' => 'Number of sacks must be a whole or half sack (example: 2 or 2.5).',
            'sacks.min' => 'Number of sacks must be at least half a sack (0.5).',
        ]);
        $delivery = $service->record($validated, Auth::id());

        return redirect()->route('owner.claim-stub', $delivery->id)
            ->with('success', 'Delivery recorded successfully.');
    }
}
