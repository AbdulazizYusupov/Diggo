<?php

namespace App\Notifications\ShopOrder;

use App\Models\ShopOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ShopOrderStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly ShopOrder $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $message = match ($this->order->status) {
            ShopOrder::STATUS_APPROVED => "Sizning #{$this->order->order_number} buyurtmangiz tasdiqlandi.",
            ShopOrder::STATUS_REJECTED => "Sizning #{$this->order->order_number} buyurtmangiz rad etildi.",
            default => "Sizning #{$this->order->order_number} holati o\'zgardi: {$this->order->status}.",
        };

        return [
            'type'         => 'shop_order_status',
            'title'        => match ($this->order->status) {
                ShopOrder::STATUS_APPROVED => 'Buyurtma tasdiqlandi',
                ShopOrder::STATUS_REJECTED => 'Buyurtma rad etildi',
                default => 'Buyurtma holati o\'zgardi',
            },
            'message'      => $message,
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'status'       => $this->order->status,
            'action_url'   => route('student.shop.orders'),
        ];
    }
}
