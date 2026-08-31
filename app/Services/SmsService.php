<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class SmsService
{
    public function send(string $phoneNumber, string $message): array
    {
        $mode = (string) config('services.semaphore.mode', 'simulation');

        if ($mode === 'simulation') {
            return [
                'success' => true,
                'status' => 'simulated',
                'response' => 'Simulated SMS only',
            ];
        }

        try {
            $response = Http::asForm()
                ->timeout(15)
                ->post((string) config('services.semaphore.endpoint'), [
                    'apikey' => config('services.semaphore.api_key'),
                    'number' => $phoneNumber,
                    'message' => $message,
                    'sendername' => config('services.semaphore.sender_name', 'JKDIEZ'),
                ]);
        } catch (Throwable $exception) {
            report($exception);

            return [
                'success' => false,
                'status' => 'failed',
                'response' => 'Unable to connect to the SMS provider.',
            ];
        }

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
