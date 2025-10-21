<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EcoIdeaInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'eco_idea_id',
        'user_id',
        'type',
        'content',
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



