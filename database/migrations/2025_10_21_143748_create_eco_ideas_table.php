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
        Schema::create('eco_ideas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->enum('waste_type', ['organic', 'plastic', 'metal', 'e-waste', 'paper', 'glass', 'textile', 'mixed']);
            $table->enum('difficulty', ['easy', 'medium', 'hard']);
            $table->text('description');
            $table->text('ai_suggestion')->nullable();
            $table->integer('team_size_needed')->nullable();
            $table->text('team_requirements')->nullable();
            $table->text('application_description')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['pending', 'approved', 'in_progress', 'completed', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eco_ideas');
    }
};
