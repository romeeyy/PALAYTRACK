<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rice_type_recovery_rate_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rice_type_id')->constrained()->restrictOnDelete();
            $table->decimal('old_rate', 5, 2);
            $table->decimal('new_rate', 5, 2);
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('changed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rice_type_recovery_rate_histories');
    }
};
