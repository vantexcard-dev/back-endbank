<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeController extends Controller
{
    public function createIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        
        try {
            $intent = PaymentIntent::create([
                'amount' => $request->amount * 100, // Convertir en centimes
                'currency' => $request->currency,
                'metadata' => [
                    'integration_check' => 'accept_a_payment'
                ]
            ]);
            
            return response()->json([
                'clientSecret' => $intent->client_secret
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function confirmPayment(Request $request)
    {
        // Enregistrer le paiement dans votre base de données
        $payment = Payment::create([
            'payment_intent_id' => $request->payment_intent_id,
            'amount' => $request->amount,
            'status' => $request->status,
            'payment_method' => 'stripe',
            'user_id' => auth()->id() // ou récupérez l'utilisateur autrement
        ]);
        
        return response()->json(['success' => true]);
    }
}