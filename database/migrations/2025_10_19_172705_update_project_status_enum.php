<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Adjust enum values to match controllers/views
        Schema::table('eco_ideas', function (Blueprint $table) {
            if (Schema::hasColumn('eco_ideas', 'project_status')) {
                $table->dropColumn('project_status');
            }
        });
        Schema::table('eco_ideas', function (Blueprint $table) {
            $table->enum('project_status', ['idea','recruiting','in_progress','completed','verified','donated'])->default('idea');
        });
    }

    public function down(): void
    {
        Schema::table('eco_ideas', function (Blueprint $table) {
            $table->dropColumn('project_status');
        });
        Schema::table('eco_ideas', function (Blueprint $table) {
            $table->enum('project_status', ['idea','recruiting','active','completed','verified'])->default('idea');
        });
    }
};
