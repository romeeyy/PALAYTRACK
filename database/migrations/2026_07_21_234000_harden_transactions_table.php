<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $duplicateDelivery = DB::table('transactions')
            ->select('delivery_id')
            ->groupBy('delivery_id')
            ->havingRaw('COUNT(*) > 1')
            ->value('delivery_id');

        $duplicateReference = DB::table('transactions')
            ->whereNotNull('reference_number')
            ->where('reference_number', '<>', '')
            ->select('reference_number')
            ->groupBy('reference_number')
            ->havingRaw('COUNT(*) > 1')
            ->value('reference_number');

        if ($duplicateDelivery !== null || $duplicateReference !== null) {
            throw new RuntimeException(
                'Transaction safeguards were not applied because existing duplicate delivery or digital reference records need manual review.'
            );
        }

        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('actual_rice_kg', 'palay_weight_kg');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->unique('delivery_id', 'transactions_delivery_unique');
            $table->unique('reference_number', 'transactions_reference_unique');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropUnique('transactions_reference_unique');
            $table->dropUnique('transactions_delivery_unique');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('palay_weight_kg', 'actual_rice_kg');
        });
    }
};
