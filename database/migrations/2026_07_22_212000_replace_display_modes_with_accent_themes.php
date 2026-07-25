<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('theme_preference', 10)->default('classic')->change();
        });

        DB::table('users')
            ->whereNotIn('theme_preference', ['classic', 'forest', 'emerald'])
            ->update(['theme_preference' => 'classic']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('theme_preference', 10)->default('system')->change();
        });

        DB::table('users')
            ->whereIn('theme_preference', ['classic', 'forest', 'emerald'])
            ->update(['theme_preference' => 'system']);
    }
};
