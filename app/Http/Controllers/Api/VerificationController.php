<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Verification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'passport' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $user = Auth::user();

        $passportPath = $request->file('passport')->store("verifications/{$user->id}/passport", 'public');
        $proofPath = $request->file('proof')->store("verifications/{$user->id}/proof", 'public');

        $verification = Verification::create([
            'user_id' => $user->id,
            'passport_path' => $passportPath,
            'proof_path' => $proofPath,
        ]);

        return response()->json([
            'message' => 'Documents reçus avec succès.',
            'verification' => $verification,
        ]);
    }
    public function submitSelfie(Request $request)
    {
        $request->validate([
            'selfie' => 'required|image|max:5120', // max 5MB
        ]);

        $user = Auth::user();

        $path = $request->file('selfie')->store('selfies', 'public');

        // Met à jour la vérification ou la crée si inexistante
        $verification = Verification::updateOrCreate(
            ['user_id' => $user->id],
            ['selfie_path' => $path]
        );

        return response()->json([
            'message' => 'Selfie enregistré avec succès',
            'verification' => $verification,
        ]);
    }
}
