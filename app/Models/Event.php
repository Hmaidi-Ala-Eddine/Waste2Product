<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'description',
        'image',
        'date_time',
        'likes',
        'participants_count',
        'user_id',
    ];

    protected $casts = [
        'likes' => 'integer',
        'participants_count' => 'integer',
        'date_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Users who liked this event
     */
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_likes')->withTimestamps();
    }

    /**
     * Users who participate in this event
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_participants')->withTimestamps();
    }

    public function incrementLikes(): void
    {
        $this->increment('likes');
    }

    public function incrementParticipants(): void
    {
        $this->increment('participants_count');
    }

    public function isLikedBy($userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function isParticipatedBy($userId): bool
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }
}
