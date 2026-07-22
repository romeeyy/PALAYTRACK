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
        Schema::create('milling_fee_histories', function (Blueprint $table) {
    $table->id();
    $table->string('milling_type');
    $table->decimal('old_fee', 10, 2);
    $table->decimal('new_fee', 10, 2);
    $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('changed_at')->useCurrent();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milling_fee_histories');
    }
};
