<?php

namespace App\Notifications\AttendanceMarked;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttendanceMarkedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    use Queueable;

    public function __construct(
        private readonly string $status,
        private readonly int    $dailyCoins,
        private readonly int    $homeworkScore,
        private readonly int    $activityScore,
        private readonly string $lessonDate,
        private readonly string $lessonTime,
        private readonly string $groupName,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // return ['mail'];
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
    public function toArray(object $notifiable): array
    {
        $statusLabel = match ($this->status) {
            'present' => '✅ Keldi',
            'late'    => '⏰ Kechikdi',
            'absent'  => '❌ Kelmadi',
            'excused' => '📋 Sababli',
            default   => $this->status,
        };

        $parts = [];
        if ($this->homeworkScore > 0) $parts[] = "Uy vazifasi: +{$this->homeworkScore}";
        if ($this->activityScore > 0) $parts[] = "Faollik: +{$this->activityScore}";
        $extra = $parts ? ' (' . implode(', ', $parts) . ')' : '';

        return [
            'title'      => "Davomat: {$statusLabel}",
            'message'    => "{$this->groupName} — {$this->lessonDate} {$this->lessonTime}. Bugungi coin: +{$this->dailyCoins}{$extra}",
            'coins'      => $this->dailyCoins,
            'status'     => $this->status,
            'group'      => $this->groupName,
            'date'       => $this->lessonDate,
            'action_url' => '/student/attendance',
        ];
    }
}
