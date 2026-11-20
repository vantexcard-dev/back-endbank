<?php

namespace App\Http\Controllers\Api;   // ← AJOUTE \Api

use App\Http\Controllers\Controller;  // ← import explicite du parent
use Illuminate\Http\Request;
use App\Models\Card;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    public function toggleStatus($id)
    {
        $user = Auth::user();

        $card = Card::where('id', $id)->where('user_id', $user->id)->first();

        if (!$card) {
            return response()->json(['error' => 'Carte non trouvée'], 404);
        }

        $card->status = $card->status === 'active' ? 'disabled' : 'active';
        $card->save();

        return response()->json($card);
    }
}