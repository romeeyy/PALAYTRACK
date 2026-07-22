<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained()->onDelete('cascade');
            $table->enum('method', ['call', 'text', 'in_person']);
            $table->enum('notification_status', ['sent', 'reached', 'failed']);
            $table->text('remarks')->nullable();
            $table->dateTime('notified_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_notifications');
    }
};