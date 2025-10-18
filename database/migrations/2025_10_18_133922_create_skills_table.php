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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "engineering", "design", "ai_specialist", "marketing"
            $table->text('description');
            $table->json('keywords'); // ["build", "construct", "engineer", "technical"]
            $table->json('related_waste_types'); // ["plastic", "metal", "e-waste"]
            $table->json('difficulty_levels'); // ["easy", "medium", "hard"]
            $table->integer('priority_score')->default(1); // 1-5 importance
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};