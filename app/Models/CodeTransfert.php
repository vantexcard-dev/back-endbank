<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CodeTransfert extends Model
{
    use HasFactory;

    protected $table = 'codes_transfert';

    protected $fillable = [
        'user_id',
        'code',
        'etape',
        'utilise',
    ];

    /**
     * Relation : un code appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Générer un code aléatoire unique lors de la création.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->code = strtoupper(Str::random(10));
        });
    }

    /**
     * Retourne le pourcentage de progression selon l'étape actuelle.
     */
    public function getProgressAttribute()
    {
        return match ($this->etape) {
            'initiation' => 30,
            'confirmation' => 90,
            'finalisation' => 100,
            default => 0,
        };
    }

    /**
     * Vérifie si le code a déjà été utilisé.
     */
    public function isUsed(): bool
    {
        return $this->utilise;
    }
}
