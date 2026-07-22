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
            $table->timestamp('completed_at')->nullable()->after('delivered_at');
            $table->timestamp('claimed_at')->nullable()->after('completed_at');
        });

        DB::table('deliveries')
            ->whereIn('status', ['completed', 'claimed'])
            ->orderBy('id')
            ->each(function ($delivery): void {
                $completedAt = DB::table('inventory_logs')
                    ->where('delivery_id', $delivery->id)
                    ->where('stock_category', 'milled_rice')
                    ->where('type', 'in')
                    ->min('logged_at');

                $completedAt ??= DB::table('delivery_notifications')
                    ->where('delivery_id', $delivery->id)
                    ->min('created_at');

                DB::table('deliveries')
                    ->where('id', $delivery->id)
                    ->update([
                        'completed_at' => $completedAt ?? $delivery->updated_at,
                        'claimed_at' => $delivery->status === 'claimed'
                            ? $delivery->updated_at
                            : null,
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn(['completed_at', 'claimed_at']);
        });
    }
};
