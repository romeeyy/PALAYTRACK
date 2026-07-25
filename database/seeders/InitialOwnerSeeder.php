<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class InitialOwnerSeeder extends Seeder
{
    public function run(): void
    {
        $email = trim((string) env('INITIAL_OWNER_EMAIL'));
        $password = (string) env('INITIAL_OWNER_PASSWORD');
        $name = trim((string) env('INITIAL_OWNER_NAME', 'Test Owner'));

        if ($email === '' || $password === '') {
            $this->command?->warn('Initial owner skipped: INITIAL_OWNER_EMAIL or INITIAL_OWNER_PASSWORD is missing.');
            return;
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('INITIAL_OWNER_EMAIL must be a valid email address.');
        }

        if (Str::length($password) < 8 || ! preg_match('/[A-Za-z]/', $password) || ! preg_match('/[0-9]/', $password)) {
            throw new RuntimeException('INITIAL_OWNER_PASSWORD must contain at least 8 characters, a letter, and a number.');
        }

        if (User::where('role', 'owner')->exists()) {
            $this->command?->info('Initial owner already exists; no account was changed.');
            return;
        }

        User::create([
            'name' => $name !== '' ? $name : 'Test Owner',
            'email' => strtolower($email),
            'password' => Hash::make($password),
            'role' => 'owner',
            'is_active' => true,
        ]);

        $this->command?->info('Initial test owner account created.');
    }
}
