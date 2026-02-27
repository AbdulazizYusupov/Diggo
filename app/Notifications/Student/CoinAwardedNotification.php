<?php

namespace App\Notifications\Student;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CoinAwardedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    use Queueable;

    public function __construct(
        public readonly int    $coins,
        public readonly string $description,
        public readonly string $lessonDate
    ) {}

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
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }
    public function toDatabase(object $notifiable): array
    {
        return [
            'coins'       => $this->coins,
            'description' => $this->description,
            'lesson_date' => $this->lessonDate,
            'message'     => "Sizga +{$this->coins} coin berildi!",
            'type'        => 'coin_awarded',
        ];
    }

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
