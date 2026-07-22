<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RiceType;
use App\Services\RiceTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RiceTypeController extends Controller
{
    public function index()
    {
        return response()->json(RiceType::latest()->get());
    }

    public function store(Request $request, RiceTypeService $riceTypeService)
    {
        $this->ensureOwner();

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:rice_types,name',
            'recovery_rate' => 'required|numeric|min:0.01|max:100',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ]);

        $riceType = $riceTypeService->create($validated);

        return response()->json([
            'message' => 'Rice type created successfully.',
            'data' => $riceType,
        ], 201);
    }

    public function show(RiceType $riceType)
    {
        return response()->json($riceType);
    }

    public function update(Request $request, RiceType $riceType, RiceTypeService $riceTypeService)
    {
        $this->ensureOwner();

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:rice_types,name,' . $riceType->id,
            'recovery_rate' => 'required|numeric|min:0.01|max:100',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ]);

        $riceType = $riceTypeService->update($riceType, $validated, Auth::user());

        return response()->json([
            'message' => 'Rice type updated successfully.',
            'data' => $riceType,
        ]);
    }

    public function destroy(RiceType $riceType)
    {
        $this->ensureOwner();

        throw ValidationException::withMessages([
            'rice_type' => 'Rice types cannot be permanently deleted. Mark the rice type as inactive instead.',
        ]);
    }

    private function ensureOwner(): void
    {
        abort_unless(Auth::check() && Auth::user()->role === 'owner', 403);
    }
}
