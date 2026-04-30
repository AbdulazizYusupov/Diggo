<?php

namespace App\Notifications\PaymentApprove;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DirectorCommissionNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private readonly Payment $payment,
        private readonly float $commissionAmount,
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $studentName = $this->payment->payable?->user?->full_name ?? 'N/A';

        return [
            'title' => 'Komissiya Qabul Qilindi',
            'message' => $studentName . ' ning to\'lovidan komissiya: ' . number_format($this->commissionAmount, 2) . ' so\'m',
            'payment_id' => $this->payment->id,
            'student_name' => $studentName,
            'commission_amount' => $this->commissionAmount,
            'paid_at' => $this->payment->created_at->toDateTimeString(),
            'action_url' => route('payments.show', $this->payment->id),
        ];
    }
}
