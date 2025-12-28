<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderShippedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    /**
     * Create a new notification instance.
     */
    public function __construct(public \App\Models\Order $order)
    {
        //
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Order Shipped!',
            'message' => 'Your order #' . $this->order->id . ' has been shipped.',
            'action_url' => url('/dashboard/orders/' . $this->order->id),
            'type' => 'order_shipped',
            'data' => [
                'order_id' => $this->order->id,
                'tracking_number' => $this->order->tracking_number
            ]
        ];
    }
}
