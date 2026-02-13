<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'lecture_id',
        'status',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }
}
