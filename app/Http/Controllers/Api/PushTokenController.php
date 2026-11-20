<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PushToken;

class PushTokenController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        $pushToken = PushToken::updateOrCreate(
            ['token' => $request->token],
            ['user_id' => $user->id]
        );

        return response()->json([
            'message' => 'Push token enregistré avec succès',
            'data' => $pushToken,
        ]);
    }
}
