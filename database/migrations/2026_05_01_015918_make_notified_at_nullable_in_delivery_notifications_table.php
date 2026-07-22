<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_notifications', function (Blueprint $table) {
            $table->dateTime('notified_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('delivery_notifications', function (Blueprint $table) {
            $table->dateTime('notified_at')->nullable(false)->change();
        });
    }
};