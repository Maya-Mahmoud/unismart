<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Lecture;

class StudentLectureReminder extends Notification implements ShouldQueue
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
            ->subject('Lecture Reminder - Tomorrow')
            ->line('You have a lecture tomorrow!')
            ->line('Lecture: ' . $this->lecture->title)
            ->line('Time: ' . $this->lecture->start_time->format('H:i'))
            ->action('View My Schedule', url('/student/dashboard'))
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
                'message' => 'You have a lecture tomorrow',
                'type' => 'info',
            ];
        }
        
        $subjectName = $lecture->subject?->name ?? ($lecture->subject ?? 'Unknown Subject');
        $hallName = $lecture->hall?->hall_name ?? 'Unknown Hall';
        $lectureTime = $lecture->start_time->format('H:i');
        
        return [
            'title' => 'Lecture Tomorrow',
            'message' => "لديك محاضرة غداً الساعة {$lectureTime} بالقاعة {$hallName} - {$subjectName}",
            'type' => 'info',
            'lecture_id' => $lecture->id,
            'subject' => $subjectName,
            'start_time' => $lecture->start_time->format('Y-m-d H:i'),
            'hall' => $hallName,
            'hall_id' => $lecture->hall_id,
        ];
    }
}
