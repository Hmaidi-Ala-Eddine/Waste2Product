<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcoIdea extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'creator_id',
        'title',
        'waste_type',
        'difficulty',
        'description',
        'ai_suggestion',
        'team_size_needed',
        'team_requirements',
        'application_description',
        'image',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'team_size_needed' => 'integer',
        ];
    }

    /**
     * Available waste types
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

    /**
     * Available difficulty levels
     */
    public static function getDifficulties(): array
    {
        return [
            'easy' => 'Easy',
            'medium' => 'Medium',
            'hard' => 'Hard',
        ];
    }

    /**
     * Available statuses
     */
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

    /**
     * Get the creator of the idea
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Scope to filter by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by waste type
     */
    public function scopeWasteType($query, $wasteType)
    {
        return $query->where('waste_type', $wasteType);
    }

    /**
     * Scope to filter by difficulty
     */
    public function scopeDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    /**
     * Get the formatted waste type
     */
    public function getWasteTypeFormattedAttribute()
    {
        return self::getWasteTypes()[$this->waste_type] ?? $this->waste_type;
    }

    /**
     * Get the formatted difficulty
     */
    public function getDifficultyFormattedAttribute()
    {
        return self::getDifficulties()[$this->difficulty] ?? $this->difficulty;
    }

    /**
     * Get the formatted status
     */
    public function getStatusFormattedAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    /**
     * Get the status badge class for UI
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'in_progress' => 'bg-info',
            'completed' => 'bg-dark',
            'rejected' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Get the difficulty badge class for UI
     */
    public function getDifficultyBadgeClassAttribute()
    {
        return match($this->difficulty) {
            'easy' => 'bg-success',
            'medium' => 'bg-warning',
            'hard' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('assets/img/default-idea.jpg');
    }
}
