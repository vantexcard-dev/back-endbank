<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    //
    use HasFactory;
    
    
    protected $fillable = [
        'user_id',
        'passport_path',
        'proof_path',
        'selfie_path',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
