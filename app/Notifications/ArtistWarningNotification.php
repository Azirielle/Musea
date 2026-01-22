<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArtistWarningNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly string $reason,
        public readonly ?string $adminNotes = null,
    ) {}

    public function via(object $notifiable): array
    {
        // Database for in-app notifications; mail as a fallback if configured.
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Account Warning',
            'message' => 'A report about your account has been reviewed and resulted in a warning.',
            'reason' => $this->reason,
            'admin_notes' => $this->adminNotes,
            'action_url' => '/profile',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Musea: Account Warning')
            ->greeting('Hello ' . ($notifiable->first_name ?? 'Artist') . ',')
            ->line('A report about your account has been reviewed and resulted in a warning.')
            ->line('Reason: ' . $this->reason);

        if ($this->adminNotes) {
            $mail->line('Admin notes: ' . $this->adminNotes);
        }

        return $mail->action('View your account', url('/profile'));
    }
}

