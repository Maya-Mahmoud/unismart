<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    protected $fillable = [
        'user_id', 
        'lecture_file_id', 
        'score', 
        'correct_answers', 
        'total_questions'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة النتيجة بملف المحاضرة (كل نتيجة تنتمي لملف معين)
    public function lectureFile()
    {
        return $this->belongsTo(LectureFile::class);
    }
}
