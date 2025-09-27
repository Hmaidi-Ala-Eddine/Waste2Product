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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->unique();
            }

            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable();
            }

            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable();
            }

            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->index();
                $table->timestamp('phone_verified_at')->nullable();
            }

            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable();
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->index();
            }

            if (!Schema::hasColumn('users', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable();
            }

            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->index();
            }

            if (!Schema::hasColumn('users', 'faceid_enabled')) {
                $table->boolean('faceid_enabled')->default(false);
            }

            if (!Schema::hasColumn('users', 'forgot_password_token')) {
                $table->string('forgot_password_token')->nullable()->index();
                $table->timestamp('forgot_password_token_sent_at')->nullable();
            }

            if (!Schema::hasColumn('users', 'jwt_token')) {
                $table->text('jwt_token')->nullable();
                $table->timestamp('jwt_expires_at')->nullable();
            }

            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->index();
            }
        });

        // Ensure password reset tokens table exists (Laravel default)
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'username', 'first_name', 'last_name', 'phone', 'phone_verified_at',
                'address', 'role', 'profile_photo_path', 'is_active', 'faceid_enabled',
                'forgot_password_token', 'forgot_password_token_sent_at', 'jwt_token',
                'jwt_expires_at', 'last_login_at'
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        if (Schema::hasTable('password_reset_tokens')) {
            Schema::dropIfExists('password_reset_tokens');
        }
    }
};
