<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherCourseAssignNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $courseTitle, $courseId, $action;

    public function __construct($courseTitle, $courseId, $action)
    {
        $this->courseTitle = $courseTitle;
        $this->courseId = $courseId;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        // return [
        //     'message' => "You have been assigned to the course: {$this->courseTitle}",
        //     'course_id' => $this->courseId,
        // ];
        
        // return [
        //     'message'   => "You have been assigned to the course: {$this->courseTitle}",
        //     'course_id' => $this->courseId,
        //     'url'       => route('profile.notifications.read', $this->id) // auto read link
        //     ];

        $message = match($this->action) {
            'assigned' => "You have been assigned to the course: {$this->courseTitle}",
            'removed'  => "You have been removed from the course: {$this->courseTitle}",
            'updated'  => "Your assignment has been updated for the course: {$this->courseTitle}",
            default    => "Notification for course: {$this->courseTitle}",
        };

        return [
            'message'   => $message,
            'course_id' => $this->courseId,
            'action'    => $this->action
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    // public function via(object $notifiable): array
    // {
    //     return ['mail'];
    // }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         //
    //     ];
    // }
}
