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
        Schema::table('waste_requests', function (Blueprint $table) {
            // Vérifie si la colonne n'existe pas déjà
            if (!Schema::hasColumn('waste_requests', 'state')) {
                $table->string('state')->after('quantity'); // Tunisia governorate
                $table->index('state'); // For faster filtering
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waste_requests', function (Blueprint $table) {
            if (Schema::hasColumn('waste_requests', 'state')) {
                $table->dropIndex(['state']);
                $table->dropColumn('state');
            }
        });
    }
};
