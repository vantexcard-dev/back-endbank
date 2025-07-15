<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfert extends Model
{
    // Nom de la table si elle n'est pas en suivant la convention (c'est-à-dire 'transferts')
    protected $table = 'transferts';

    // Les attributs qui peuvent être remplis par affectation de masse
    protected $fillable = [
        'sender_name',
        'recipient_name',
        'sender_account_number',
        'recipient_account_number',
        'montant',
        'devise',
        'etat',
    ];

    // Optionnel: Si vous avez des colonnes de type datetime
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Définir une relation entre Transfert et CompteBancaire pour l'expéditeur
     * (en supposant que l'expéditeur est lié à un compte bancaire).
     */
    public function expediteur()
    {
        return $this->belongsTo(CompteBancaire::class, 'sender_account_number', 'account_number');
    }

    /**
     * Définir une relation entre Transfert et CompteBancaire pour le destinataire
     * (en supposant que le destinataire est lié à un compte bancaire).
     */
    public function destinataire()
    {
        return $this->belongsTo(CompteBancaire::class, 'recipient_account_number', 'account_number');
    }

    // Vous pouvez ajouter des méthodes supplémentaires si nécessaire.
}
