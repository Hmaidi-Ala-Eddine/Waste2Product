<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EcoIdeaSimilarity extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_idea_id',
        'similar_idea_id',
        'similarity_percentage',
        'similarity_details',
        'warning_sent',
    ];

    protected $casts = [
        'similarity_percentage' => 'decimal:2',
        'similarity_details' => 'array',
        'warning_sent' => 'boolean',
    ];

    public function originalIdea(): BelongsTo
    {
        return $this->belongsTo(EcoIdea::class, 'original_idea_id');
    }

    public function similarIdea(): BelongsTo
    {
        return $this->belongsTo(EcoIdea::class, 'similar_idea_id');
    }
}



