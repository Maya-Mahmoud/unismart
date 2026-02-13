<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubjectAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'lecture_id',
        'presence_count',
        'absence_count',
    ];

    protected $casts = [
        'presence_count' => 'integer',
        'absence_count' => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function lecture()
{
    return $this->belongsTo(Lecture::class, 'lecture_id');
}

}
