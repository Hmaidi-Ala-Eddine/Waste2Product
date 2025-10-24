<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'user_id',
        'user_name',
        'comment',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the post that owns the comment.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user that owns the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the moderated (censored) version of the comment
     *
     * @return string
     */
    public function getModeratedCommentAttribute(): string
    {
        $groqService = app(\App\Services\GroqService::class);
        $moderation = $groqService->moderateComment($this->comment);
        
        return $moderation['censored_text'] ?? $this->comment;
    }

    /**
     * Check if comment contains inappropriate content
     *
     * @return bool
     */
    public function hasInappropriateContent(): bool
    {
        $groqService = app(\App\Services\GroqService::class);
        $moderation = $groqService->moderateComment($this->comment);
        
        return !($moderation['is_appropriate'] ?? true);
    }
}