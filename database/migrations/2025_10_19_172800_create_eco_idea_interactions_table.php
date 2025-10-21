<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eco_idea_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eco_idea_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['comment', 'like', 'dislike']);
            $table->text('content')->nullable();
            $table->timestamps();
            // Removed unique constraint to allow multiple comments per user
            // Likes are still handled in application code to prevent duplicates
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eco_idea_interactions');
    }
};
