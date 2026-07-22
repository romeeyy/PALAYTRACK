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
            $table->date('queue_date')->nullable()->after('queue_number');
        });

        DB::table('deliveries')
            ->select(['id', 'delivered_at', 'created_at'])
            ->orderBy('id')
            ->chunkById(500, function ($deliveries): void {
                foreach ($deliveries as $delivery) {
                    $sourceDate = $delivery->delivered_at ?: $delivery->created_at;

                    DB::table('deliveries')->where('id', $delivery->id)->update([
                        'queue_date' => substr((string) $sourceDate, 0, 10),
                    ]);
                }
            });

        Schema::table('deliveries', function (Blueprint $table) {
            $table->unique(['queue_date', 'queue_number'], 'deliveries_daily_queue_unique');
            $table->index(['status', 'delivered_at'], 'deliveries_status_date_index');
        });

        Schema::create('delivery_queue_counters', function (Blueprint $table) {
            $table->date('queue_date')->primary();
            $table->unsignedInteger('last_number')->default(0);
            $table->timestamps();
        });

        DB::table('deliveries')
            ->select('queue_date', DB::raw('MAX(queue_number) as last_number'))
            ->whereNotNull('queue_date')
            ->groupBy('queue_date')
            ->orderBy('queue_date')
            ->get()
            ->each(function ($queue): void {
                DB::table('delivery_queue_counters')->insert([
                    'queue_date' => $queue->queue_date,
                    'last_number' => $queue->last_number,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_queue_counters');

        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropIndex('deliveries_status_date_index');
            $table->dropUnique('deliveries_daily_queue_unique');
            $table->dropColumn('queue_date');
        });
    }
};
