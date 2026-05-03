<?php

namespace App\Notifications\ShopOrder;

use App\Models\ShopOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ShopOrderCreatedNotification extends Notification implements ShouldQueue
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
        return [
            'type'       => 'shop_order_created',
            'title'      => 'Yangi buyurtma:',
            'message'    => "#{$this->order->order_number} buyurtmasi qabul qilindi. Student: {$this->order->student?->user?->name}.",
            'order_id'   => $this->order->id,
            'order_number' => $this->order->order_number,
            'total_coins' => $this->order->total_coins,
            'action_url' => route('admin.shop.orders.show', $this->order),
        ];
    }
}
