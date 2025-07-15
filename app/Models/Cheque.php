<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cheque_number',
        'amount',
        'status',
        'encashed_at', // Ajout du champ 'encashed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function encash()
    {
        $this->status = 'cleared'; // Mettre à jour le statut à 'cleared'
        $this->encashed_at = now(); // Ajouter la date de l'encaissement
        $this->save(); // Sauvegarder les modifications
    }
}
