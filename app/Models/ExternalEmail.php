<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalEmail extends Model
{
    protected $fillable = [
        'recipient_email',
        'recipient_name',
        'subject',
        'content',
        'template',
        'variables',
        'sent_at'
    ];

    protected $casts = [
        'variables' => 'array',
        'sent_at' => 'datetime'
    ];

    public function sendEmail()
    {
        \Mail::to($this->recipient_email)
            ->send(new \App\Mail\ExternalEmailMailable($this));
            
        $this->update(['sent_at' => now()]);
    }
}