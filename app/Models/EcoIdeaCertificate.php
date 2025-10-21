<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EcoIdeaCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'eco_idea_id',
        'user_id',
        'certificate_id',
        'role_in_project',
        'contribution_summary',
        'skills_demonstrated',
        'certificate_file_path',
        'verification_url',
    ];

    protected $casts = [
        'skills_demonstrated' => 'array',
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



