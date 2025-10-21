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
        Schema::table('eco_idea_tasks', function (Blueprint $table) {
            // 1. Make assigned_to nullable
            $table->foreignId('assigned_to')->nullable()->change();
            
            // 2. Change priority from integer to string
            $table->string('priority', 20)->default('medium')->change();
            
            // 3. Update description to be nullable
            $table->text('description')->nullable()->change();
        });
        
        // 4. Fix status enum values - must drop and recreate
        \DB::statement("ALTER TABLE eco_idea_tasks MODIFY COLUMN status ENUM('todo', 'in_progress', 'review', 'completed') DEFAULT 'todo'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eco_idea_tasks', function (Blueprint $table) {
            $table->foreignId('assigned_to')->nullable(false)->change();
            $table->integer('priority')->default(1)->change();
            $table->text('description')->nullable(false)->change();
        });
        
        \DB::statement("ALTER TABLE eco_idea_tasks MODIFY COLUMN status ENUM('todo', 'in_progress', 'done') DEFAULT 'todo'");
    }
};
