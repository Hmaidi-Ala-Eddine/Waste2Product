<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subject',
        'date_time',
        'image',
        'description',
        'author_id',
        'engagement',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_time' => 'datetime',
            'engagement' => 'integer',
        ];
    }

    /**
     * Get the author of the event
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get all participations for this event
     */
    public function participations()
    {
        return $this->hasMany(EventParticipation::class);
    }

    /**
     * Check if a user has participated in this event
     */
    public function isParticipatedBy($userId)
    {
        return $this->participations()->where('user_id', $userId)->exists();
    }

    /**
     * Get the formatted date and time
     */
    public function getFormattedDateTimeAttribute()
    {
        return $this->date_time ? $this->date_time->format('d/m/Y H:i') : 'N/A';
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('assets/img/default-event.jpg');
    }

    /**
     * Scope to filter upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date_time', '>=', now());
    }

    /**
     * Scope to filter past events
     */
    public function scopePast($query)
    {
        return $query->where('date_time', '<', now());
    }
}
