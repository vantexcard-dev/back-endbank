<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerEmailMailable extends Mailable
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
            ->markdown("emails.customer.{$this->email->template}")
            ->with([
                'content' => $this->email->content,
                'user' => $this->email->user,
            ]);
    }
}