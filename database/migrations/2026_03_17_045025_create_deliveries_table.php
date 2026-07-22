<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_id')->unique();
            $table->unsignedInteger('queue_number');
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->string('client_name');
            $table->string('contact_number');
            $table->foreignId('rice_type_id')->constrained()->onDelete('cascade');
            $table->integer('sacks');
            $table->decimal('palay_weight', 10, 2);
            $table->decimal('recovery_rate', 5, 2);
            $table->decimal('estimated_rice', 10, 2);
            $table->decimal('actual_rice', 10, 2)->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'claimed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
