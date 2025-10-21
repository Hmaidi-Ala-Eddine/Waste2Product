<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EcoIdea extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'title',
        'description',
        'waste_type',
        'image_path',
        'ai_generated_suggestion',
        'difficulty_level',
        'upvotes',
        'status',
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
        'similarity_score',
    ];

    protected $casts = [
        'upvotes' => 'integer',
        'team_requirements' => 'string',
        'impact_metrics' => 'array',
        'ai_suggested_skills' => 'array',
        'start_date' => 'date',
        'completion_date' => 'date',
        'verification_date' => 'date',
        'is_recruiting' => 'boolean',
        'donated_to_ngo' => 'boolean',
        'ai_confidence_level' => 'decimal:2',
        'similarity_score' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(EcoIdeaApplication::class);
    }

    public function team(): HasMany
    {
        return $this->hasMany(EcoIdeaTeam::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(EcoIdeaTask::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(EcoIdeaCertificate::class);
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(EcoIdeaInteraction::class);
    }

    public function similarities(): HasMany
    {
        return $this->hasMany(EcoIdeaSimilarity::class, 'original_idea_id');
    }

    /**
     * Static helpers used by admin Blade filters
     */
    public static function getWasteTypes(): array
    {
        return [
            'organic' => 'Organic Waste',
            'plastic' => 'Plastic',
            'metal' => 'Metal',
            'e-waste' => 'Electronic Waste',
            'paper' => 'Paper',
            'glass' => 'Glass',
            'textile' => 'Textile',
            'mixed' => 'Mixed Waste',
        ];
    }

    public static function getDifficulties(): array
    {
        return [
            'easy' => 'Easy',
            'medium' => 'Medium',
            'hard' => 'Hard',
        ];
    }

    public static function getStatuses(): array
    {
        return [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'rejected' => 'Rejected',
        ];
    }
}
