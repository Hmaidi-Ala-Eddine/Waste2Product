<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EcoProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'eco_idea_id',
        'user_id',
        'name',
        'description',
        'status',
        'ai_impact_prediction',
        'image_path',
        'completion_date',
    ];

    protected $casts = [
        'ai_impact_prediction' => 'array',
        'completion_date' => 'date',
    ];

    public function ecoIdea(): BelongsTo
    {
        return $this->belongsTo(EcoIdea::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}






