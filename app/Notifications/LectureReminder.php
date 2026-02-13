<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Lecture;

class LectureReminder extends Notification implements ShouldQueue
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
            ->subject('Lecture Reminder')
            ->line('You have a lecture starting soon.')
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
        return [
            'lecture_id' => $this->lecture->id,
            'title' => $this->lecture->title,
            'subject' => $this->lecture->subject,
            'start_time' => $this->lecture->start_time->format('Y-m-d H:i'),
            'hall' => $this->lecture->hall->hall_name,
            'message' => "You have a lecture in 30 minutes in {$this->lecture->hall->hall_name}.",
        ];
    }
}
