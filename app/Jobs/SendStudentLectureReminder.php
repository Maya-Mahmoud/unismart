<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Lecture;
use App\Models\User;
use App\Notifications\StudentLectureReminder;

class SendStudentLectureReminder implements ShouldQueue
{
    use Queueable;

    protected $lecture;

    /**
     * Create a new job instance.
     */
    public function __construct(Lecture $lecture)
    {
        $this->lecture = $lecture;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Reload the lecture to ensure it still exists and hasn't been deleted
        $lecture = Lecture::with(['subject', 'hall', 'user', 'department'])->find($this->lecture->id);
        if (!$lecture || !$lecture->subject) {
            return;
        }

        // Get students from the same department and year as the lecture's subject
        $students = User::where('role', 'student')
            ->where('department_id', $lecture->department_id)
            ->whereHas('student', function ($query) use ($lecture) {
                $query->where('year', $lecture->subject->year);
            })
            ->get();

        // Send reminder to each student
        foreach ($students as $student) {
            $student->notify(new StudentLectureReminder($lecture));
        }
    }
}
