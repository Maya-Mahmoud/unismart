<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Lecture;

class LectureCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $lecture;

    /**
     * Create a new notification instance.
     */
    public function __construct(Lecture $lecture)
    {
        $this->lecture = $lecture;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Lecture Scheduled')
            ->line('A new lecture has been scheduled.')
            ->action('View Lecture', url('/lectures'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Reload lecture to ensure relationships are loaded
        $lecture = Lecture::with(['subject', 'hall', 'user'])->find($this->lecture->id);
        
        if (!$lecture) {
            return [
                'title' => 'Lecture Notification',
                'message' => 'A lecture has been scheduled',
                'type' => 'info',
            ];
        }
        
        // Handle subject - it might be a string or an object
        if (is_object($lecture->subject)) {
            $subjectName = $lecture->subject->name ?? 'Unknown Subject';
        } else {
            $subjectName = $lecture->subject ?? 'Unknown Subject';
        }
        
        $hallName = $lecture->hall?->hall_name ?? 'Unknown Hall';
        $professorName = $lecture->user?->name ?? 'Unknown Professor';
        
        return [
            'title' => 'New Lecture: ' . $subjectName,
            'message' => "A new lecture '{$lecture->title}' has been scheduled on {$lecture->start_time->format('l, F j')} at {$lecture->start_time->format('g:i A')} in {$hallName}.",
            'type' => 'info',
            'lecture_id' => $lecture->id,
            'subject' => $subjectName,
            'subject_id' => $lecture->subject_id,
            'start_time' => $lecture->start_time->format('Y-m-d H:i'),
            'end_time' => $lecture->end_time->format('Y-m-d H:i'),
            'hall' => $hallName,
            'hall_id' => $lecture->hall_id,
            'professor' => $professorName,
        ];
    }
}
