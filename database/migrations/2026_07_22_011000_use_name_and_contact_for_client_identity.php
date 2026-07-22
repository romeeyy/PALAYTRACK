<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropUnique('clients_contact_unique');
            $table->unique(['name', 'contact_number'], 'clients_name_contact_unique');
        });

        foreach (DB::table('deliveries')->orderBy('id')->get() as $delivery) {
            $client = DB::table('clients')
                ->where('name', trim($delivery->client_name))
                ->where('contact_number', $delivery->contact_number)
                ->first();

            if (! $client) {
                $clientId = DB::table('clients')->insertGetId([
                    'name' => trim($delivery->client_name),
                    'contact_number' => $delivery->contact_number,
                    'client_type' => 'regular',
                    'created_at' => $delivery->created_at ?? now(),
                    'updated_at' => now(),
                ]);
            } else {
                $clientId = $client->id;
            }

            DB::table('deliveries')->where('id', $delivery->id)->update(['client_id' => $clientId]);
        }
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropUnique('clients_name_contact_unique');
            $table->unique('contact_number', 'clients_contact_unique');
        });
    }
};
