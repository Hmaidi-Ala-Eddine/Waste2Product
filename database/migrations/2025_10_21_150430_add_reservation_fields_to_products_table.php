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
        Schema::table('products', function (Blueprint $table) {
            $table->string('reserved_by')->nullable()->after('status');
            $table->timestamp('reserved_at')->nullable()->after('reserved_by');
            $table->text('reserved_message')->nullable()->after('reserved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['reserved_by', 'reserved_at', 'reserved_message']);
        });
    }
};
