<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Lecture;
use App\Models\Student;
use App\Models\User;
use App\Notifications\LectureStartedQRCodeReady;
use App\Notifications\StudentLectureStartedScanQR;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendLectureStartedNotifications implements ShouldQueue
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
        $lecture = Lecture::with(['user', 'department', 'subject'])->find($this->lecture->id);
        if (!$lecture) {
            return;
        }

        Log::info('Sending lecture started notifications for lecture: ' . $lecture->id);

        // Send QR Code Ready notification to professor
        if ($lecture->user) {
            $lecture->user->notify(new LectureStartedQRCodeReady($lecture));
            Log::info('Sent QR Code ready notification to professor: ' . $lecture->user->id);
        }

        // Send Scan QR notification to all students in the same department
        if ($lecture->department_id) {
            $students = User::where('role', 'student')
                ->where('department_id', $lecture->department_id)
                ->get();

            foreach ($students as $student) {
                $student->notify(new StudentLectureStartedScanQR($lecture));
            }
            Log::info('Sent scan QR notifications to ' . $students->count() . ' students');
        }
    }
}




