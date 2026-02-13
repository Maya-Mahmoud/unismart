<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hall extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hall_name',
        'capacity',
        'building',
        'floor',
        'equipment',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function currentBooking()
    {
        return $this->hasOne(Booking::class)->where('status', 'booked')->latest();
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }

    /**
     * Check if the hall is occupied at a specific datetime (by lectures or bookings).
     */
    public function isOccupiedAt($datetime)
    {
        // Check for overlapping lectures
        $overlappingLecture = $this->lectures()
            ->where('start_time', '<=', $datetime)
            ->where('end_time', '>', $datetime)
            ->exists();

        // Check for overlapping bookings (if they have end_time)
        $overlappingBooking = $this->bookings()
            ->where('status', 'booked')
            ->where('booked_at', '<=', $datetime)
            ->when($this->bookings()->first()?->end_time, function ($query) use ($datetime) {
                return $query->where('end_time', '>', $datetime);
            })
            ->exists();

        return $overlappingLecture || $overlappingBooking;
    }

    /**
     * Update hall status based on current lectures and bookings.
     */
    public function updateStatusBasedOnLectures()
    {
        $now = now();
        $status = $this->isOccupiedAt($now) ? 'booked' : 'available';
        $this->update(['status' => $status]);
    }

}
