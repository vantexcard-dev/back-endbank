<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Card;
use Illuminate\Support\Facades\Auth;

class CardStatusController extends Controller
{
    /**
     * Basculer le statut de la carte (activer/désactiver).
     */
    public function toggle($id)
    {
        $card = Card::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->first();

        if (!$card) {
            return response()->json(['message' => 'Carte introuvable ou non autorisée.'], 404);
        }

        $card->status = $card->status === 'active' ? 'inactive' : 'active';
        $card->save();

        return response()->json([
            'message' => 'Statut de la carte mis à jour.',
            'card' => $card
        ], 200);
    }
}
