<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eco_idea_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eco_idea_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('application_message');
            $table->string('resume_path');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();
            $table->unique(['eco_idea_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eco_idea_applications');
    }
};
