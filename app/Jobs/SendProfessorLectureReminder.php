<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Lecture;
use App\Notifications\ProfessorLectureReminder;
use Carbon\Carbon;

class SendProfessorLectureReminder implements ShouldQueue
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
        $lecture = Lecture::find($this->lecture->id);
        if (!$lecture || !$lecture->user) {
            return;
        }

        // Send reminder notification to the professor
        $lecture->user->notify(new ProfessorLectureReminder($lecture));
    }
}





