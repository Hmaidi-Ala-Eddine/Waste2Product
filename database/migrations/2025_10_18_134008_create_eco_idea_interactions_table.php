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
        Schema::create('eco_idea_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eco_idea_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['comment', 'upvote', 'downvote']);
            $table->text('content')->nullable(); // For comments
            $table->timestamps();
            
            $table->unique(['eco_idea_id', 'user_id', 'type']); // One vote per user per idea
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eco_idea_interactions');
    }
};