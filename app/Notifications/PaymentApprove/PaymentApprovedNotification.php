<?php

namespace App\Notifications\PaymentApprove;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
//  implements ShouldQueue
class PaymentApprovedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private readonly Payment $payment) {}

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

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
   
    public function toArray(object $notifiable): array
    {
        return [
            'title'       => 'Payment Approved',
            'message'     => 'Your payment of ' . number_format($this->payment->amount, 2) . ' has been approved.',
            'payment_id'  => $this->payment->id,
            'amount'      => $this->payment->amount,
            'paid_at'     => $this->payment->created_at->toDateTimeString(),
            // 'action_url'  => route('student.payments.show', $this->payment->id),
            'action_url' => route('student.payments.show', $this->payment->id),
        ];
    }
}
