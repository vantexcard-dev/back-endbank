<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    /**
     * Les attributs autorisés à l'assignation de masse.
     */
    protected $fillable = [
        'user_id',
        'amount',
        'interest_rate',
        'total_interest',
        'repayment_years',
        'payment_date',
        'status',
    ];

    /**
     * Le format des colonnes de type date.
     */
    protected $dates = [
        'payment_date',
    ];

    /**
     * Relation avec l'utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifie si le prêt est remboursé.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
