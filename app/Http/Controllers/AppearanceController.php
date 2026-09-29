<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppearanceController extends Controller
{
    public function edit()
    {
        $this->authorizedUser();

        return view('appearance');
    }

    public function update(Request $request)
    {
        $this->authorizedUser();

        $validated = $request->validate([
            'theme_preference' => ['required', 'in:classic,forest,emerald,olive,sage,palay'],
            'display_mode' => ['required', 'in:light,dark,system'],
        ]);

        User::findOrFail(Auth::id())->update($validated);

        return back()->with('success', 'Appearance preference updated successfully.');
    }

    private function authorizedUser(): void
    {
        abort_unless(
            Auth::check() && in_array(Auth::user()->role, ['owner', 'staff'], true),
            403
        );
    }
}
