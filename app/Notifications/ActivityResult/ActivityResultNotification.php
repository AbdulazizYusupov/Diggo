<?php

namespace App\Notifications\ActivityResult;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivityResultNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private readonly string $activityName,
        private readonly string $activityType,
        private readonly int    $earnedCoins,
        private readonly int    $maxCoins,
        private readonly ?int   $rank,
        private readonly string $activityDate,
        private readonly string $groupName,
        private readonly ?string $note,
    ) {
        //
    }

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
    public function toArray(object $notifiable): array
    {
        $typeLabel = match ($this->activityType) {
            'competition'  => '🏆 Musobaqa',
            'olympiad'     => '🎓 Olimpiada',
            'hackathon'    => '💻 Xakaton',
            'presentation' => '📊 Taqdimot',
            'project'      => '🚀 Loyiha',
            default        => '⭐ Faoliyat',
        };

        $rankLabel = '';
        if ($this->rank !== null) {
            $rankLabel = match ($this->rank) {
                1 => ' 🥇 1-o\'rin',
                2 => ' 🥈 2-o\'rin',
                3 => ' 🥉 3-o\'rin',
                default => " {$this->rank}-o'rin",
            };
        }

        $percentage = $this->maxCoins > 0
            ? round(($this->earnedCoins / $this->maxCoins) * 100)
            : 0;

        $noteText = $this->note ? " | {$this->note}" : '';

        return [
            'type'         => 'activity_result',
            'title'        => "{$typeLabel}{$rankLabel} — +{$this->earnedCoins} coin!",
            'message'      => "{$this->groupName}: \"{$this->activityName}\" ({$this->activityDate}). "
                . "Coin: {$this->earnedCoins}/{$this->maxCoins} ({$percentage}%){$noteText}",
            'earned_coins' => $this->earnedCoins,
            'max_coins'    => $this->maxCoins,
            'percentage'   => $percentage,
            'rank'         => $this->rank,
            'activity'     => $this->activityName,
            'activity_type' => $this->activityType,
            'group'        => $this->groupName,
            'date'         => $this->activityDate,
            'action_url'   => '/student/coins',
        ];
    }
}
