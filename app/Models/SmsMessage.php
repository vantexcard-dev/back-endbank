<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Twilio\Rest\Client;

class SmsMessage extends Model
{
    protected $fillable = [
        'user_id',
        'phone_number',
        'message',
        'status',
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sendSms()
    {
        // Implémentez votre service SMS ici (ex: Twilio, Nexmo, etc.)
        $success = $this->sendViaSmsGateway();

        if ($success) {
            $this->update([
                'status' => 'sent',
                'sent_at' => now()
            ]);
        }

        return $success;
    }

    protected function sendViaSmsGateway()
    {
        // Exemple avec Twilio (à adapter)
        try {
            $twilio = new Client(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );

            $twilio->messages->create(
                $this->phone_number,
                [
                    'from' => config('services.twilio.from'),
                    'body' => $this->message
                ]
            );

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}
