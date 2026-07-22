<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->string('milling_type_at_delivery', 20)->nullable()->after('client_type_at_delivery');
            $table->decimal('milling_fee_per_kg_at_delivery', 10, 2)->nullable()->after('milling_type_at_delivery');
        });

        Schema::create('system_setting_histories', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('changed_at');
            $table->timestamps();
            $table->index(['key', 'changed_at']);
        });

        $menudo = (float) (DB::table('settings')->where('key', 'menudo_fee')->value('value') ?? 2.00);
        $commercial = (float) (DB::table('settings')->where('key', 'commercial_fee')->value('value') ?? 1.50);

        DB::table('deliveries')->orderBy('id')->eachById(function ($delivery) use ($menudo, $commercial): void {
            $type = ($delivery->client_type_at_delivery === 'commercial' || (float) $delivery->palay_weight >= 500)
                ? 'commercial' : 'menudo';
            DB::table('deliveries')->where('id', $delivery->id)->update([
                'milling_type_at_delivery' => $type,
                'milling_fee_per_kg_at_delivery' => $type === 'commercial' ? $commercial : $menudo,
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_setting_histories');
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn(['milling_type_at_delivery', 'milling_fee_per_kg_at_delivery']);
        });
    }
};
