<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EcoIdeaTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'eco_idea_id',
        'assigned_to',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
        'priority' => 'integer',
    ];

    public function ecoIdea(): BelongsTo
    {
        return $this->belongsTo(EcoIdea::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}