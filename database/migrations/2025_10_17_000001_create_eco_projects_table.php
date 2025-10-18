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
        Schema::create('eco_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eco_idea_id')->constrained('eco_ideas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->enum('status', ['prototype', 'testing', 'completed', 'failed'])->default('prototype');
            $table->json('ai_impact_prediction')->nullable();
            $table->string('image_path')->nullable();
            $table->date('completion_date')->nullable();
            $table->timestamps();

            $table->index(['eco_idea_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eco_projects');
    }
};






