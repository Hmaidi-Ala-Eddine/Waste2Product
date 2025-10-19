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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('waste_type', ['organic', 'plastic', 'metal', 'e-waste', 'paper', 'glass', 'textile', 'mixed']);
            $table->string('image_path')->nullable();
            $table->text('ai_generated_suggestion')->nullable();
            $table->enum('difficulty_level', ['easy', 'medium', 'hard']);
            $table->integer('upvotes')->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['waste_type', 'difficulty_level']);
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






