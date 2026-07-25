<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    private function profileUserOnly(): void
    {
        abort_unless(Auth::check() && in_array(Auth::user()->role, ['owner', 'staff'], true), 403);
    }

    public function edit()
    {
        $this->profileUserOnly();
        return view('owner.profile');
    }

    public function update(Request $request)
    {
        $this->profileUserOnly();
        $request->merge([
            'name' => preg_replace('/\s+/', ' ', trim((string) $request->name)),
            'email' => strtolower(trim((string) $request->email)),
        ]);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore(Auth::id())],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);

        $user = User::findOrFail(Auth::id());
        $oldPhoto = $user->profile_photo_path;
        $profileData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->hasFile('profile_photo')) {
            $directory = public_path('uploads/profile-pictures');
            File::ensureDirectoryExists($directory);
            $filename = Str::uuid() . '.' . $request->file('profile_photo')->extension();
            $request->file('profile_photo')->move($directory, $filename);
            $profileData['profile_photo_path'] = 'uploads/profile-pictures/' . $filename;
        } elseif ($request->boolean('remove_photo')) {
            $profileData['profile_photo_path'] = null;
        }

        $user->update($profileData);

        if (($request->hasFile('profile_photo') || $request->boolean('remove_photo')) && $oldPhoto) {
            $oldPhotoPath = public_path($oldPhoto);
            if (str_starts_with($oldPhoto, 'uploads/profile-pictures/') && File::isFile($oldPhotoPath)) {
                File::delete($oldPhotoPath);
            }
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $this->profileUserOnly();

        $validated = $request->validateWithBag('passwordUpdate', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $user = User::findOrFail(Auth::id());

        if (Hash::check($validated['password'], $user->password)) {
            return back()->withErrors([
                'password' => 'The new password must be different from your current password.',
            ], 'passwordUpdate');
        }

        $user->update(['password' => $validated['password']]);

        return back()->with('password_success', 'Password updated successfully.');
    }
}
