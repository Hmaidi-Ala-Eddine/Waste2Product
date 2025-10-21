<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'profile_picture')) {
                $table->string('profile_picture')->nullable()->after('profile_photo_path');
            }
        });

        // Backfill profile_picture from profile_photo_path if available
        if (Schema::hasColumn('users', 'profile_photo_path') && Schema::hasColumn('users', 'profile_picture')) {
            try {
                DB::table('users')
                    ->whereNull('profile_picture')
                    ->update(['profile_picture' => DB::raw('profile_photo_path')]);
            } catch (\Throwable $e) {
                // Ignore failures during backfill (e.g., when DB permissions prevent updates in some envs)
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'profile_picture')) {
                $table->dropColumn('profile_picture');
            }
        });
    }
};
