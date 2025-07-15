<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLink extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'amount',
        'token',
        'status',
    ];

    /**
     * Relation avec l'utilisateur qui a généré le lien.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les transactions associées à ce lien de paiement.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Génère un token unique pour le lien de paiement.
     */
    public static function generateToken(): string
    {
        return \Illuminate\Support\Str::random(32);
    }

    /**
     * Vérifie si le lien de paiement est expiré.
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    /**
     * Marque le lien de paiement comme payé.
     */
    public function markAsPaid(): void
    {
        $this->update(['status' => 'paid']);
    }

    /**
     * Marque le lien de paiement comme expiré.
     */
    public function markAsExpired(): void
    {
        $this->update(['status' => 'expired']);
    }
}