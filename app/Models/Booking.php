<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hall_id',
        'booked_at',
        'end_time',
        'status',
    ];

    protected $casts = [
        'booked_at' => 'datetime',
        'end_time' => 'datetime',
        'status' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hall(): BelongsTo
    {
        return $this->belongsTo(Hall::class);
    }

    /**
     * Check if this booking overlaps with another lecture or booking.
     */
    public function overlapsWith($other)
    {
        $thisEnd = $this->end_time ?? $this->booked_at->addHours(1); // Default to 1 hour if no end_time
        if ($other instanceof \App\Models\Lecture) {
            return $this->booked_at < $other->end_time && $thisEnd > $other->start_time;
        } elseif ($other instanceof Booking) {
            $otherEnd = $other->end_time ?? $other->booked_at->addHours(1);
            return $this->booked_at < $otherEnd && $thisEnd > $other->booked_at;
        }
        return false;
    }
}
