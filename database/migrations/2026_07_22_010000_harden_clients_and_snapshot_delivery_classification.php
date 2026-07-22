<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function normalize(?string $value): string
    {
        $digits = preg_replace('/\D+/', '', trim((string) $value));
        return str_starts_with($digits, '639') ? '0' . substr($digits, 2) : $digits;
    }

    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->string('client_type_at_delivery', 20)->nullable()->after('client_id');
        });

        Schema::create('client_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->foreignId('owner_id')->constrained('users')->restrictOnDelete();
            $table->string('field', 50);
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->timestamp('changed_at');
            $table->timestamps();
            $table->index(['client_id', 'changed_at']);
        });

        $keepers = [];
        foreach (DB::table('clients')->orderBy('id')->get() as $client) {
            $contact = $this->normalize($client->contact_number);
            if (isset($keepers[$contact])) {
                $keeperId = $keepers[$contact];
                DB::table('deliveries')->where('client_id', $client->id)->update(['client_id' => $keeperId]);
                if ($client->client_type === 'commercial') {
                    DB::table('clients')->where('id', $keeperId)->update(['client_type' => 'commercial']);
                }
                DB::table('clients')->where('id', $client->id)->delete();
            } else {
                DB::table('clients')->where('id', $client->id)->update(['contact_number' => $contact]);
                $keepers[$contact] = $client->id;
            }
        }

        foreach (DB::table('deliveries')->whereNull('client_id')->orderBy('id')->get() as $delivery) {
            $contact = $this->normalize($delivery->contact_number);
            $clientId = $keepers[$contact] ?? null;
            if (! $clientId) {
                $clientId = DB::table('clients')->insertGetId([
                    'name' => trim($delivery->client_name), 'contact_number' => $contact,
                    'client_type' => 'regular', 'created_at' => now(), 'updated_at' => now(),
                ]);
                $keepers[$contact] = $clientId;
            }
            DB::table('deliveries')->where('id', $delivery->id)->update([
                'client_id' => $clientId, 'contact_number' => $contact,
            ]);
        }

        DB::table('deliveries')->whereNull('client_type_at_delivery')->orderBy('id')->eachById(function ($delivery) {
            $type = $delivery->client_id
                ? DB::table('clients')->where('id', $delivery->client_id)->value('client_type')
                : 'regular';
            DB::table('deliveries')->where('id', $delivery->id)->update([
                'client_type_at_delivery' => $type ?: 'regular',
            ]);
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->unique('contact_number', 'clients_contact_unique');
            $table->dropColumn('address');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->dropUnique('clients_contact_unique');
        });
        Schema::dropIfExists('client_histories');
        Schema::table('deliveries', fn (Blueprint $table) => $table->dropColumn('client_type_at_delivery'));
    }
};
