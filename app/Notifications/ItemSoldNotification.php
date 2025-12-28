<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ItemSoldNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    /**
     * Create a new notification instance.
     */
    public function __construct(public \App\Models\OrderItem $orderItem)
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
            'title' => 'Item Sold!',
            'message' => 'Your artwork "' . $this->orderItem->artwork->title . '" has been sold.',
            'action_url' => url('/dashboard/artworks'),
            'type' => 'item_sold',
            'data' => [
                'artwork_id' => $this->orderItem->artwork_id,
                'price' => $this->orderItem->price
            ]
        ];
    }
}
