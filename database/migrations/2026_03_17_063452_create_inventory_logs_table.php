<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('stock_category', ['palay', 'milled_rice']);
            $table->enum('type', ['in', 'out']);
            $table->decimal('quantity', 12, 2);
            $table->string('remarks')->nullable();
            $table->dateTime('logged_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};