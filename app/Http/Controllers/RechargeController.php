<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Account;

class RechargeController extends Controller
{
    /**
     * Confirme la recharge après validation PayPal
     */
    public function confirm(Request $request)
    {
        $user = Auth::user();

        // Vérifier les données JSON envoyées depuis le frontend
        $data = $request->all();

        $amount = $data['purchase_units'][0]['amount']['value'] ?? null;
        $currency = $data['purchase_units'][0]['amount']['currency_code'] ?? 'EUR';
        $paypalTransactionId = $data['id'] ?? null;

        if (!$amount || !$paypalTransactionId) {
            return response()->json(['error' => 'Données de paiement invalides'], 400);
        }

        // On suppose que l'utilisateur a un compte actif
        $account = $user->accounts()->first(); // ou autre logique si plusieurs comptes

        if (!$account) {
            return response()->json(['error' => 'Aucun compte bancaire trouvé.'], 404);
        }

        // Crée la transaction
        Transaction::create([
            'sender_account_id' => null,
            'receiver_account_id' => $account->id,
            'sender_name' => 'PayPal Recharge',
            'receiver_name' => $user->name,
            'sender_iban' => null,
            'receiver_iban' => $account->iban,
            'sender_bic' => null,
            'receiver_bic' => $account->bic,
            'amount' => $amount,
            'currency' => $currency,
            'type' => 'recharge',
            'status' => 'success',
        ]);

        // Créditer le compte
        $account->balance += $amount;
        $account->save();

        return response()->json(['success' => true, 'message' => 'Compte rechargé avec succès.']);
    }
}
