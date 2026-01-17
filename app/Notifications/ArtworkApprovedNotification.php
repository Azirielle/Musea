<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArtworkApprovedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    /**
     * Create a new notification instance.
     */
    public function __construct(public \App\Models\Artwork $artwork)
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
            'title' => 'Artwork Approved!',
            'message' => 'Your artwork "' . $this->artwork->title . '" has been approved and is now live.',
            'action_url' => route('shop.show', $this->artwork->id),
            'type' => 'artwork_approved',
            'data' => [
                'artwork_id' => $this->artwork->id
            ]
        ];
    }
}
