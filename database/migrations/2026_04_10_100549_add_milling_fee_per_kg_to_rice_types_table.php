<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rice_types', function (Blueprint $table) {
            $table->decimal('milling_fee_per_kg', 8, 2)->default(0)->after('recovery_rate');
        });
    }

    public function down(): void
    {
        Schema::table('rice_types', function (Blueprint $table) {
            $table->dropColumn('milling_fee_per_kg');
        });
    }
};