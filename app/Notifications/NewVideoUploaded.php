<?php

namespace App\Notifications;

use App\Models\Course;
use App\Models\CourseVideo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewVideoUploaded extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $course;
    protected $video;
    
    public function __construct(Course $course, CourseVideo $video)
    {
        $this->course = $course;
        $this->video = $video;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }
    // public function via(object $notifiable): array
    // {
    //     return ['mail'];
    // }

    public function toDatabase($notifiable){
        return [
            'course_id'     => $this->course->id,
            'course_title'  => $this->course->title,
            'video_id'      => $this->video->id,
            'video_title'   => $this->video->title,
            'message'       => "New video '{$this->video->title}' has been uploaded in course '{$this->course->title}'."
        ];
    }

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
