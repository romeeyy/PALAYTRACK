<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    private function ownerOnly(): void { abort_unless(Auth::check() && Auth::user()->role === 'owner', 403); }
    public function edit() { $this->ownerOnly(); return view('owner.profile'); }
    public function update(Request $request)
    {
        $this->ownerOnly();
        $request->merge([
            'name' => preg_replace('/\s+/', ' ', trim((string) $request->name)),
            'email' => strtolower(trim((string) $request->email)),
        ]);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore(Auth::id())],
        ]);
        User::findOrFail(Auth::id())->update($validated);
        return back()->with('success', 'Profile updated successfully.');
    }
}
