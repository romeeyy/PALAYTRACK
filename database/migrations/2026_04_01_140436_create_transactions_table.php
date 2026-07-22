<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('delivery_id')->constrained('deliveries')->onDelete('cascade');

            $table->decimal('milling_fee_per_kg', 10, 2)->default(0);
            $table->decimal('actual_rice_kg', 10, 2)->default(0);

            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('other_charges', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);

            $table->string('payment_method')->default('cash');
            $table->decimal('amount_received', 10, 2)->default(0);
            $table->decimal('change_amount', 10, 2)->default(0);

            $table->string('reference_number')->nullable();
            $table->string('payment_status')->default('paid');
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};