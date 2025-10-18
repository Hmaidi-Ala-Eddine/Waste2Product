<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'keywords',
        'related_waste_types',
        'difficulty_levels',
        'priority_score',
    ];

    protected $casts = [
        'keywords' => 'array',
        'related_waste_types' => 'array',
        'difficulty_levels' => 'array',
        'priority_score' => 'integer',
    ];
}