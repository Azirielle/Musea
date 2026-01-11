<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class FundsReleasedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Order $order)
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
        return ['database']; // Changed delivery channel
    }

    /**
     * Get the mail representation of the notification.
     */
    // Removed toMail method as per the instruction implying database notification only.
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
            'title' => 'Funds Released!',
            'message' => 'Order #' . $this->order->order_number . ' has been completed. Funds have been added to your wallet.',
            'action_url' => route('dashboard.wallet'),
            'type' => 'funds_released',
            'data' => [
                'order_id' => $this->order->id,
                'order_number' => $this->order->order_number,
                'amount' => $this->order->total_amount, // or calculate share
            ]
        ];
    }
}
