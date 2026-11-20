<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TransferVerificationCode extends Notification
{
    use Queueable;

    protected $code;
    protected $name; // ✅ Ajout de l’attribut name

    /**
     * Create a new notification instance.
     */
    public function __construct(string $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Code de vérification de transfert')
            ->view('emails.transfer_code', [
                'code' => $this->code,
                'name' => $this->name // ✅ maintenant défini
            ]);
    }

    /**
     * Get the array representation of the notification (optional).
     */
    public function toArray(object $notifiable): array
    {
        return [
            'code' => $this->code,
        ];
    }
}
