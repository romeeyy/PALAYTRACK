<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    public function send(string $phoneNumber, string $message): array
    {
        
        $mode = env('SMS_MODE', 'simulation');

        if ($mode === 'simulation') {
            return [
                'success' => true,
                'status' => 'simulated',
                'response' => 'Simulated SMS only',
            ];
        }

        $response = Http::asForm()->post('https://api.semaphore.co/api/v4/messages', [
            'apikey' => env('SEMAPHORE_API_KEY'),
            'number' => $phoneNumber,
            'message' => $message,
            'sendername' => env('SEMAPHORE_SENDER_NAME', 'JKDIEZ'),
        ]);

        if ($response->successful()) {
            $data = $response->json();

            return [
                'success' => true,
                'status' => $data[0]['status'] ?? 'sent',
                'response' => $data,
            ];
        }

        return [
            'success' => false,
            'status' => 'failed',
            'response' => $response->body(),
        ];
    }
}