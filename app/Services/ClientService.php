<?php

namespace App\Services;

use App\Models\Client;

class ClientService
{
    public function normalizeContact(string $contact): string
    {
        $digits = preg_replace('/\D+/', '', trim($contact));
        if (str_starts_with($digits, '639')) {
            $digits = '0' . substr($digits, 2);
        }

        return $digits;
    }

    public function normalizeName(string $name): string
    {
        return mb_convert_case(preg_replace('/\s+/', ' ', trim($name)), MB_CASE_TITLE, 'UTF-8');
    }

    public function resolve(string $name, string $contact): Client
    {
        $normalizedName = $this->normalizeName($name);
        return Client::firstOrCreate(
            ['name' => $normalizedName, 'contact_number' => $this->normalizeContact($contact)],
            ['client_type' => 'regular']
        );
    }
}
