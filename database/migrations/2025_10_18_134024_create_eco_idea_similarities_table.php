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
        Schema::create('eco_idea_similarities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('original_idea_id')->constrained('eco_ideas')->onDelete('cascade');
            $table->foreignId('similar_idea_id')->constrained('eco_ideas')->onDelete('cascade');
            $table->decimal('similarity_percentage', 5, 2);
            $table->json('similarity_details'); // What parts are similar
            $table->boolean('warning_sent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eco_idea_similarities');
    }
};