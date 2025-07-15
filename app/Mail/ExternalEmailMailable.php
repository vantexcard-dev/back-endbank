<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExternalEmailMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject($this->email->subject)
            ->markdown('emails.external.'.$this->email->template ?: 'general')
            ->with([
                'content' => $this->email->content,
                'recipient' => $this->email->recipient_name,
            ]);
    }
}