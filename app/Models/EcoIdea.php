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
        'user_id',
        'title',
        'description',
        'waste_type',
        'image_path',
        'ai_generated_suggestion',
        'difficulty_level',
        'upvotes',
        'status',
    ];

    protected $casts = [
        'upvotes' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ecoProjects(): HasMany
    {
        return $this->hasMany(EcoProject::class);
    }
}






