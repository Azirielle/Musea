<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage|\App\Mail\OrderReceipt
    {
        return (new \App\Mail\OrderReceipt($this->order))
            ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Order Placed Successfully!',
            'message' => 'Thank you for your purchase. Order #' . $this->order->id,
            'action_url' => url('/dashboard/orders/' . $this->order->id),
            'type' => 'order_placed',
            'data' => [
                'order_id' => $this->order->id,
                'amount' => $this->order->total_amount
            ]
        ];
    }
}
