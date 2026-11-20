<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditCardRequest;
use Illuminate\Support\Facades\Auth;

class CreditCardRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:virtual,physical',
        ]);

        $user = Auth::user();

        // Empêcher les doublons de demande si déjà en attente
        $existing = CreditCardRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Vous avez déjà une demande en attente.'
            ], 409);
        }

        $cardRequest = CreditCardRequest::create([
            'user_id' => auth()->id(),
            'type' => 'virtual', // <-- bien entouré de quotes
        ]);

        return response()->json([
            'message' => 'Demande de carte envoyée avec succès.',
            'data' => $cardRequest
        ], 201);
    }
}

