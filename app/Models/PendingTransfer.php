<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PendingTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipient',
        'receiver_iban',
        'receiver_bic',
        'amount',
        'currency',
        'reason',
        'verification_code',
        'validated',
        'expires_at',
    ];

    protected $casts = [
        'validated' => 'boolean',
        'amount' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    // Relation : chaque transfert appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Vérifie si le code est expiré
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    // Formatte la date d’expiration si tu veux l'afficher joliment
    public function getExpiresInHumanAttribute()
    {
        return Carbon::parse($this->expires_at)->diffForHumans();
    }
}
