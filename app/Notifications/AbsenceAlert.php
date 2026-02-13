<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Student;

class AbsenceAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $student;
    protected $highAbsenceSubjects;

    /**
     * Create a new notification instance.
     */
    public function __construct(Student $student, array $highAbsenceSubjects = [])
    {
        $this->student = $student;
        $this->highAbsenceSubjects = $highAbsenceSubjects;
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
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $message = 'You have exceeded the allowed absence limit. You must attend the upcoming lectures to avoid suspension.';

        if (!empty($this->highAbsenceSubjects)) {
            $subjectDetails = [];
            foreach ($this->highAbsenceSubjects as $subject) {
                $subjectDetails[] = "{$subject['subject_name']}: {$subject['absence_percentage']}% absence ({$subject['absence_count']}/{$subject['total_lectures']} lectures)";
            }
            $message .= "\n\nSubjects with high absence rates:\n" . implode("\n", $subjectDetails);
        }

        return [
            'title' => 'Absence Alert',
            'message' => $message,
            'type' => 'warning',
            'student_id' => $this->student->id,
            'student_name' => $this->student->user->name,
            'high_absence_subjects' => $this->highAbsenceSubjects,
        ];
    }
}
