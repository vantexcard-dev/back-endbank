<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Account;

class PayPalController extends Controller
{
    public function checkout(Request $request)
    {
        $amount = $request->query('amount');

        if (!$amount || !is_numeric($amount)) {
            return redirect()->route('paypal.error')->with('error', 'Montant invalide');
        }

        return view('paypal.checkout', [
            'amount' => number_format((float) $amount, 2, '.', ''),
        ]);
    }

    public function validatePayment(Request $request)
    {
        $user = Auth::user(); // L'utilisateur connecté
        $data = $request->input('details');

        // Vérification des données
        if (!$data || !isset($data['status']) || $data['status'] !== 'COMPLETED') {
            return response()->json(['success' => false, 'error' => 'Transaction non valide.'], 400);
        }

        $amount = $data['purchase_units'][0]['amount']['value'] ?? null;
        $currency = $data['purchase_units'][0]['amount']['currency_code'] ?? 'EUR';
        $paypalTransactionId = $data['id'] ?? null;

        if (!$amount || !$paypalTransactionId) {
            return response()->json(['success' => false, 'error' => 'Données incomplètes'], 422);
        }

        // Vérifier doublon
        if (Transaction::where('paypal_transaction_id', $paypalTransactionId)->exists()) {
            return response()->json(['success' => false, 'error' => 'Transaction déjà traitée.'], 409);
        }

        $account = $user->accounts()->first();

        if (!$account) {
            return response()->json(['success' => false, 'error' => 'Aucun compte trouvé'], 404);
        }

        // DB transaction pour atomicité
        DB::transaction(function () use ($account, $user, $amount, $currency, $paypalTransactionId) {
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
                'paypal_transaction_id' => $paypalTransactionId,
            ]);

            $account->balance += $amount;
            $account->save();
        });

        return response()->json(['success' => true, 'balance' => $account->balance]);
    }
}
