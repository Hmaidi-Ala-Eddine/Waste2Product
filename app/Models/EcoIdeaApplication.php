<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EcoIdeaApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'eco_idea_id',
        'user_id',
        'application_message',
        'resume_path',
        'status',
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



