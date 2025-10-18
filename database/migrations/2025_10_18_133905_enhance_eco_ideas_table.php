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
        Schema::table('eco_ideas', function (Blueprint $table) {
            // Rename user_id to creator_id for clarity
            $table->renameColumn('user_id', 'creator_id');
            
            // Add new fields for team management
            $table->json('team_requirements')->nullable(); // {"engineers": 2, "designers": 1}
            $table->integer('team_size_needed')->default(0);
            $table->integer('team_size_current')->default(0);
            $table->text('application_description')->nullable();
            $table->boolean('is_recruiting')->default(false);
            
            // Add project execution fields
            $table->enum('project_status', ['idea', 'recruiting', 'active', 'completed', 'verified'])->default('idea');
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->date('verification_date')->nullable();
            $table->text('final_description')->nullable();
            $table->json('impact_metrics')->nullable();
            $table->boolean('donated_to_ngo')->default(false);
            $table->string('ngo_name')->nullable();
            
            // Add AI fields
            $table->json('ai_suggested_skills')->nullable();
            $table->integer('ai_suggested_team_size')->nullable();
            $table->decimal('ai_confidence_level', 5, 2)->nullable();
            $table->decimal('similarity_score', 5, 2)->nullable();
            
            // Update status enum
            $table->dropColumn('status');
        });
        
        Schema::table('eco_ideas', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eco_ideas', function (Blueprint $table) {
            $table->renameColumn('creator_id', 'user_id');
            $table->dropColumn([
                'team_requirements',
                'team_size_needed',
                'team_size_current',
                'application_description',
                'is_recruiting',
                'project_status',
                'start_date',
                'completion_date',
                'verification_date',
                'final_description',
                'impact_metrics',
                'donated_to_ngo',
                'ngo_name',
                'ai_suggested_skills',
                'ai_suggested_team_size',
                'ai_confidence_level',
                'similarity_score'
            ]);
        });
    }
};