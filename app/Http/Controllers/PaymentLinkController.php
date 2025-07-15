<?php

namespace App\Http\Controllers;

use App\Models\PaymentLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Account;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalHttp\HttpException;

class PaymentLinkController extends Controller
{
    /**
     * Crée un lien de paiement vide (sans montant).
     */
    public function createEmptyLink()
    {
        $link = PaymentLink::create([
            'user_id' => Auth::id(),
            'amount' => null, // montant vide
            'token' => PaymentLink::generateToken(),
            'status' => 'pending',
        ]);

        return redirect()->route('payment.links.show', $link->token)
            ->with('success', 'Lien de paiement vide créé avec succès.');
    }

    /**
     * Crée un lien de paiement avec un montant défini.
     */
public function createWithAmount(Request $request)
{
    $validated = $request->validate([
        'amount' => 'nullable|numeric|min:0.01',
    ]);

    $link = PaymentLink::create([
        'user_id' => auth()->id(),
        'amount' => $validated['amount'],
        'token' => PaymentLink::generateToken(),
        'status' => 'pending',
    ]);

    return redirect()->route('dashboard')->with('success', 'Lien de paiement créé avec succès.');
}


    /**
     * Affiche le lien de paiement (formulaire pour payer).
     */
    public function show($token)
    {
        $paymentLink = PaymentLink::where('token', $token)->firstOrFail();

        if ($paymentLink->isExpired()) {
            abort(403, 'Ce lien de paiement est expiré.');
        }

        // ✅ Transmet correctement la variable $link à la vue
        return view('payment-links', compact('paymentLink'));
    }

    public function process(Request $request, $token)
{
    // Récupérer le lien de paiement
    $paymentLink = PaymentLink::where('token', $token)->firstOrFail();

    // Vérifier que le lien n'est pas déjà payé ou expiré
    if ($paymentLink->status === 'paid') {
        return redirect()->back()->withErrors('Ce lien a déjà été payé.');
    }
    if ($paymentLink->status === 'expired') {
        return redirect()->back()->withErrors('Ce lien est expiré.');
    }

    // Trouver le compte de l'utilisateur destinataire (exemple: premier compte bancaire)
    $account = Account::where('user_id', $paymentLink->user_id)->first();
    if (!$account) {
        return redirect()->back()->withErrors('Compte bancaire destinataire introuvable.');
    }

    // Simuler le paiement ici (ex: appel API SteinBank), on suppose paiement OK

    DB::beginTransaction();

    try {
        // Enregistrer la transaction
        Transaction::create([
            'sender_account_id' => null, // ou id compte expéditeur si tu en as un
            'receiver_account_id' => $account->id,
            'sender_name' => null,
            'receiver_name' => $paymentLink->user->name ?? $paymentLink->user->email,
            'sender_iban' => null,
            'receiver_iban' => $account->iban,
            'sender_bic' => null,
            'receiver_bic' => $account->bic,
            'amount' => $paymentLink->amount,
            'currency' => $account->currency,
            'type' => 'payment_link',
            'status' => 'success',
        ]);

        // Créditer le compte du bénéficiaire
        $account->balance += $paymentLink->amount;
        $account->save();

        // Mettre à jour le statut du lien de paiement
        $paymentLink->markAsPaid();

        DB::commit();

        return redirect()->route('dashboard')->with('success', 'Paiement effectué avec succès.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors('Erreur lors du traitement du paiement : ' . $e->getMessage());
    }
}

public function validatePayment(Request $request)
{
    $orderID = $request->input('orderID');  // envoyé par le frontend JS PayPal

    // Exemple: récupérer le token du lien de paiement pour associer la transaction
    $token = $request->input('payment_token');
    $paymentLink = PaymentLink::where('token', $token)->firstOrFail();

    // Initialiser client PayPal (tu dois configurer ton client ailleurs)
    $client = $this->getPayPalClient();

    // Vérifier la commande PayPal
    $request = new OrdersGetRequest($orderID);

    try {
        $response = $client->execute($request);
        $order = $response->result;

        if ($order->status === "COMPLETED") {
            // Paiement réussi, enregistrer la transaction

            // Récupérer le compte destinataire (ex: premier compte)
            $account = Account::where('user_id', $paymentLink->user_id)->firstOrFail();

            \DB::beginTransaction();

            try {
                Transaction::create([
                    'sender_account_id' => null, // ou l'id du compte expéditeur si connu
                    'receiver_account_id' => $account->id,
                    'sender_name' => null,
                    'receiver_name' => $paymentLink->user->name ?? $paymentLink->user->email,
                    'sender_iban' => null,
                    'receiver_iban' => $account->iban,
                    'sender_bic' => null,
                    'receiver_bic' => $account->bic,
                    'amount' => $paymentLink->amount,
                    'currency' => $account->currency,
                    'type' => 'paypal',
                    'status' => 'success',
                ]);

                $account->balance += $paymentLink->amount;
                $account->save();

                $paymentLink->markAsPaid();

                \DB::commit();

                return response()->json(['success' => true, 'message' => 'Paiement validé et enregistré.']);
            } catch (\Exception $e) {
                \DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Erreur lors de l’enregistrement.'], 500);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Paiement non complété.'], 400);
        }
    } catch (HttpException $ex) {
        // Erreur API PayPal
        return response()->json(['success' => false, 'message' => 'Erreur lors de la vérification du paiement.'], 500);
    }
}

public function success()
{
    return view('payment.success');  // ta vue de confirmation paiement réussi
}

}
