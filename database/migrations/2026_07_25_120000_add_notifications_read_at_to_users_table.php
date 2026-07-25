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
            $table->timestamp('notifications_read_at')->nullable()->after('remember_token');
        });

        // Existing activity must not suddenly appear as unread after this feature is installed.
        DB::table('users')->whereNull('notifications_read_at')->update([
            'notifications_read_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('notifications_read_at');
        });
    }
};
