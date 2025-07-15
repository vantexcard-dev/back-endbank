<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'sender_account_id',
        'receiver_account_id',
        'sender_name',
        'receiver_name',
        'sender_iban',
        'receiver_iban',
        'sender_bic',
        'receiver_bic',
        'amount',
        'currency',
        'type',
        'status',
    ];

    /**
     * Relation avec le compte expéditeur.
     */
    public function senderAccount()
    {
        return $this->belongsTo(Account::class, 'sender_account_id');
    }

    /**
     * Relation avec le compte destinataire.
     */
    public function receiverAccount()
    {
        return $this->belongsTo(Account::class, 'receiver_account_id');
    }
}