<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropForeign(['rice_type_id']);
            $table->foreign('rice_type_id')->references('id')->on('rice_types')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropForeign(['rice_type_id']);
            $table->foreign('rice_type_id')->references('id')->on('rice_types')->cascadeOnDelete();
        });
    }
};
