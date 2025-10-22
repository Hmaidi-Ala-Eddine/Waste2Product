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
        // Create analytics table
        if (!Schema::hasTable('analytics')) {
            Schema::create('analytics', function (Blueprint $table) {
                $table->id();
                $table->date('date')->unique();
                $table->json('waste_data')->nullable();
                $table->json('product_data')->nullable();
                $table->json('order_data')->nullable();
                $table->decimal('total_waste_quantity', 10, 2)->default(0);
                $table->decimal('total_income', 10, 2)->default(0);
                $table->integer('total_products')->default(0);
                $table->integer('total_orders')->default(0);
                $table->timestamps();
            });
        }

        // Create collectors table
        if (!Schema::hasTable('collectors')) {
            Schema::create('collectors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('company_name')->nullable();
                $table->enum('vehicle_type', ['truck', 'van', 'motorcycle', 'bicycle', 'cart', 'other'])->default('other');
                $table->json('service_areas')->nullable();
                $table->decimal('capacity_kg', 8, 2)->default(0);
                $table->json('availability_schedule')->nullable();
                $table->enum('verification_status', ['pending', 'verified', 'suspended'])->default('pending');
                $table->decimal('rating', 3, 2)->nullable()->default(0);
                $table->integer('total_collections')->default(0);
                $table->text('bio')->nullable();
                $table->timestamps();
            });
        }

        // Create events table
        if (!Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->string('subject');
                $table->datetime('date_time');
                $table->string('image')->nullable();
                $table->text('description')->nullable();
                $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
                $table->integer('engagement')->default(0);
                $table->timestamps();
            });
        }

        // Create eco_ideas table
        if (!Schema::hasTable('eco_ideas')) {
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
                $table->integer('upvotes')->default(0);
                $table->enum('project_status', ['idea', 'recruiting', 'in_progress', 'completed', 'verified', 'donated'])->default('idea');
                $table->timestamps();
            });
        }

        // Create skills table
        if (!Schema::hasTable('skills')) {
            Schema::create('skills', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('category')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Create eco_idea_applications table
        if (!Schema::hasTable('eco_idea_applications')) {
            Schema::create('eco_idea_applications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('eco_idea_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('motivation')->nullable();
                $table->text('experience')->nullable();
                $table->json('skills')->nullable();
                $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
                $table->timestamps();
                
                $table->unique(['eco_idea_id', 'user_id']);
            });
        }

        // Create eco_idea_teams table
        if (!Schema::hasTable('eco_idea_teams')) {
            Schema::create('eco_idea_teams', function (Blueprint $table) {
                $table->id();
                $table->foreignId('eco_idea_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('role')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();
                
                $table->unique(['eco_idea_id', 'user_id']);
            });
        }

        // Create eco_idea_tasks table
        if (!Schema::hasTable('eco_idea_tasks')) {
            Schema::create('eco_idea_tasks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('eco_idea_id')->constrained()->onDelete('cascade');
                $table->string('title');
                $table->text('description')->nullable();
                $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
                $table->date('due_date')->nullable();
                $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
        }

        // Create eco_idea_certificates table
        if (!Schema::hasTable('eco_idea_certificates')) {
            Schema::create('eco_idea_certificates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('eco_idea_id')->constrained()->onDelete('cascade');
                $table->string('certificate_type');
                $table->string('certificate_url')->nullable();
                $table->text('description')->nullable();
                $table->date('issued_date')->nullable();
                $table->timestamps();
            });
        }

        // Create eco_idea_interactions table
        if (!Schema::hasTable('eco_idea_interactions')) {
            Schema::create('eco_idea_interactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('eco_idea_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->enum('type', ['like', 'review', 'comment'])->default('like');
                $table->text('content')->nullable();
                $table->integer('rating')->nullable();
                $table->timestamps();
                
                $table->unique(['eco_idea_id', 'user_id', 'type']);
            });
        }

        // Create eco_idea_similarities table
        if (!Schema::hasTable('eco_idea_similarities')) {
            Schema::create('eco_idea_similarities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('eco_idea_id')->constrained()->onDelete('cascade');
                $table->foreignId('similar_eco_idea_id')->constrained('eco_ideas')->onDelete('cascade');
                $table->decimal('similarity_score', 5, 4)->default(0);
                $table->timestamps();
                
                $table->unique(['eco_idea_id', 'similar_eco_idea_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eco_idea_similarities');
        Schema::dropIfExists('eco_idea_interactions');
        Schema::dropIfExists('eco_idea_certificates');
        Schema::dropIfExists('eco_idea_tasks');
        Schema::dropIfExists('eco_idea_teams');
        Schema::dropIfExists('eco_idea_applications');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('eco_ideas');
        Schema::dropIfExists('events');
        Schema::dropIfExists('collectors');
        Schema::dropIfExists('analytics');
    }
};