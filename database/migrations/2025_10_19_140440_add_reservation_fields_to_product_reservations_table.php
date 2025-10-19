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
        Schema::table('product_reservations', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('product_reservations', 'first_name')) {
                $table->string('first_name')->after('product_id');
            }
            if (!Schema::hasColumn('product_reservations', 'last_name')) {
                $table->string('last_name')->after('first_name');
            }
            if (!Schema::hasColumn('product_reservations', 'email')) {
                $table->string('email')->after('last_name');
            }
            if (!Schema::hasColumn('product_reservations', 'message')) {
                $table->text('message')->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_reservations', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'email', 'message']);
        });
    }
};