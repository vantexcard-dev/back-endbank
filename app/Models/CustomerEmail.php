<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerEmail extends Model
{
    protected $fillable = [
        'user_id',
        'subject',
        'content',
        'template',
        'variables',
        'sent_at',
    ];

    protected $casts = [
        'variables' => 'array',
        'sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sendEmail()
    {
        $user = $this->user;
        
        \Mail::to($user->email)->send(new \App\Mail\CustomerEmailMailable($this));
        
        $this->update(['sent_at' => now()]);
    }
}