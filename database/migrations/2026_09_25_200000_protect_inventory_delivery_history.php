<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->dropForeign(['delivery_id']);
        });
        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->foreign('delivery_id')->references('id')->on('deliveries')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->dropForeign(['delivery_id']);
        });
        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->foreign('delivery_id')->references('id')->on('deliveries')->nullOnDelete();
        });
    }
};
