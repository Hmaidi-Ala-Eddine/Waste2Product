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
            if (Schema::hasColumn('eco_ideas', 'difficulty') && !Schema::hasColumn('eco_ideas', 'difficulty_level')) {
                $table->renameColumn('difficulty', 'difficulty_level');
            }
            if (Schema::hasColumn('eco_ideas', 'ai_suggestion') && !Schema::hasColumn('eco_ideas', 'ai_generated_suggestion')) {
                $table->renameColumn('ai_suggestion', 'ai_generated_suggestion');
            }
            if (Schema::hasColumn('eco_ideas', 'image') && !Schema::hasColumn('eco_ideas', 'image_path')) {
                $table->renameColumn('image', 'image_path');
            }

            if (!Schema::hasColumn('eco_ideas', 'upvotes')) {
                $table->integer('upvotes')->default(0);
            }
            if (!Schema::hasColumn('eco_ideas', 'team_size_current')) {
                $table->integer('team_size_current')->default(0);
            }
            if (!Schema::hasColumn('eco_ideas', 'is_recruiting')) {
                $table->boolean('is_recruiting')->default(false);
            }
            if (!Schema::hasColumn('eco_ideas', 'project_status')) {
                $table->enum('project_status', ['idea', 'recruiting', 'active', 'completed', 'verified'])->default('idea');
            }
            if (!Schema::hasColumn('eco_ideas', 'start_date')) {
                $table->date('start_date')->nullable();
            }
            if (!Schema::hasColumn('eco_ideas', 'completion_date')) {
                $table->date('completion_date')->nullable();
            }
            if (!Schema::hasColumn('eco_ideas', 'verification_date')) {
                $table->date('verification_date')->nullable();
            }
            if (!Schema::hasColumn('eco_ideas', 'final_description')) {
                $table->text('final_description')->nullable();
            }
            if (!Schema::hasColumn('eco_ideas', 'impact_metrics')) {
                $table->json('impact_metrics')->nullable();
            }
            if (!Schema::hasColumn('eco_ideas', 'donated_to_ngo')) {
                $table->boolean('donated_to_ngo')->default(false);
            }
            if (!Schema::hasColumn('eco_ideas', 'ngo_name')) {
                $table->string('ngo_name')->nullable();
            }
            if (!Schema::hasColumn('eco_ideas', 'ai_suggested_skills')) {
                $table->json('ai_suggested_skills')->nullable();
            }
            if (!Schema::hasColumn('eco_ideas', 'ai_suggested_team_size')) {
                $table->integer('ai_suggested_team_size')->nullable();
            }
            if (!Schema::hasColumn('eco_ideas', 'ai_confidence_level')) {
                $table->decimal('ai_confidence_level', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('eco_ideas', 'similarity_score')) {
                $table->decimal('similarity_score', 5, 2)->nullable();
            }
        });

        if (Schema::hasColumn('eco_ideas', 'status')) {
            Schema::table('eco_ideas', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        Schema::table('eco_ideas', function (Blueprint $table) {
            if (!Schema::hasColumn('eco_ideas', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eco_ideas', function (Blueprint $table) {
            // Best-effort rollback for renames
            if (Schema::hasColumn('eco_ideas', 'difficulty_level')) {
                $table->renameColumn('difficulty_level', 'difficulty');
            }
            if (Schema::hasColumn('eco_ideas', 'ai_generated_suggestion')) {
                $table->renameColumn('ai_generated_suggestion', 'ai_suggestion');
            }
            if (Schema::hasColumn('eco_ideas', 'image_path')) {
                $table->renameColumn('image_path', 'image');
            }

            foreach ([
                'upvotes', 'team_size_current', 'is_recruiting', 'project_status', 'start_date',
                'completion_date', 'verification_date', 'final_description', 'impact_metrics',
                'donated_to_ngo', 'ngo_name', 'ai_suggested_skills', 'ai_suggested_team_size',
                'ai_confidence_level', 'similarity_score'
            ] as $col) {
                if (Schema::hasColumn('eco_ideas', $col)) {
                    $table->dropColumn($col);
                }
            }

            if (Schema::hasColumn('eco_ideas', 'status')) {
                $table->dropColumn('status');
            }
        });

        Schema::table('eco_ideas', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'in_progress', 'completed', 'rejected'])->default('pending');
        });
    }
};
