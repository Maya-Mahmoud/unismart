<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Lecture;

class ProfessorLectureReminder extends Notification implements ShouldQueue
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
            ->subject('Lecture Reminder - ' . $this->lecture->title)
            ->line('Your lecture is starting in 30 minutes!')
            ->action('View Lecture', url('/admin/lecture-management'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        // Reload lecture to ensure relationships are loaded
        $lecture = Lecture::with(['subject', 'hall', 'user'])->find($this->lecture->id);
        
        if (!$lecture) {
            return [
                'title' => 'Lecture Reminder',
                'message' => 'You have a lecture coming up',
                'type' => 'warning',
            ];
        }
        
        $subjectName = $lecture->subject?->name ?? ($lecture->subject ?? 'Unknown Subject');
        $hallName = $lecture->hall?->hall_name ?? 'Unknown Hall';
        
        return [
            'title' => 'Lecture Reminder',
            'message' => "You have a lecture in 30 minutes - {$lecture->title} in {$hallName}.",
            'type' => 'warning',
            'lecture_id' => $lecture->id,
            'subject' => $subjectName,
            'start_time' => $lecture->start_time->format('Y-m-d H:i'),
            'hall' => $hallName,
            'hall_id' => $lecture->hall_id,
            'professor' => $lecture->user?->name ?? 'Unknown Professor',
        ];
    }
}
