<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Lecture;
use App\Models\User;
use App\Notifications\LectureReminder;
use Carbon\Carbon;

class SendLectureReminders implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Find lectures starting in 30 minutes
        $now = Carbon::now();
        $thirtyMinutesFromNow = $now->copy()->addMinutes(30);

        $lectures = Lecture::whereBetween('start_time', [$now, $thirtyMinutesFromNow])
            ->with('user')
            ->get();

        foreach ($lectures as $lecture) {
            // Send reminder to the professor using the new ProfessorLectureReminder
            SendProfessorLectureReminder::dispatch($lecture);
        }
    }
}
