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
        Schema::create('eco_idea_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eco_idea_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['leader', 'member'])->default('member');
            $table->string('specialization')->nullable(); // "engineer", "designer", "ai_specialist"
            $table->text('responsibilities')->nullable();
            $table->enum('status', ['active', 'completed', 'left'])->default('active');
            $table->timestamps();
            
            $table->unique(['eco_idea_id', 'user_id']); // One user per idea
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eco_idea_teams');
    }
};