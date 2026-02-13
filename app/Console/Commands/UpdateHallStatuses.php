<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateHallStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-hall-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update hall statuses based on current lectures and bookings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $halls = \App\Models\Hall::all();
        $updatedCount = 0;

        foreach ($halls as $hall) {
            $oldStatus = $hall->status;
            $hall->updateStatusBasedOnLectures();
            if ($hall->status !== $oldStatus) {
                $updatedCount++;
                $this->info("Updated hall {$hall->hall_name}: {$oldStatus} -> {$hall->status}");
            }
        }

        // Handle ongoing lectures at startup
        $ongoingLectures = \App\Models\Lecture::where('start_time', '<=', now())
            ->where('end_time', '>', now())
            ->with('hall')
            ->get();

        foreach ($ongoingLectures as $lecture) {
            if ($lecture->hall && $lecture->hall->status !== 'booked') {
                $lecture->hall->update(['status' => 'booked']);
                $this->info("Set ongoing lecture hall {$lecture->hall->hall_name} to booked");
            }
        }

        $this->info("Hall statuses updated successfully. Total halls updated: {$updatedCount}");
    }
}
