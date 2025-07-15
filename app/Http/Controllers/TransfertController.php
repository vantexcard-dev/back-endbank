<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CodeTransfert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class TransfertController extends Controller
{
    public function send(Request $request)
    {
        Log::info('Début de la requête de transfert', ['request' => $request->all()]);

        $request->validate([
            'recipient' => 'required|string|max:255',
            'receiver_iban' => 'required|string',
            'receiver_bic' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'devise' => 'required|string|max:10',
            'reason' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $senderAccount = Account::where('user_id', $user->id)->first();

        if (!$senderAccount) {
            Log::error('Compte expéditeur introuvable', ['user_id' => $user->id]);
            return back()->withErrors(['error' => 'Compte expéditeur introuvable.']);
        }

        if ($senderAccount->balance < $request->amount) {
            Log::error('Solde insuffisant', ['balance' => $senderAccount->balance, 'amount' => $request->amount]);
            return back()->withErrors(['error' => 'Solde insuffisant.']);
        }

        $receiverAccount = Account::where('iban', $request->receiver_iban)->first();

        try {
            DB::beginTransaction();

            // Débit expéditeur
            $senderAccount->decrement('balance', $request->amount);

            // Crédit destinataire
            if ($receiverAccount) {
                $receiverAccount->increment('balance', $request->amount);
            }

            // Création transaction
            $transaction = Transaction::create([
                'sender_account_id' => $senderAccount->id,
                'receiver_account_id' => $receiverAccount ? $receiverAccount->id : null,
                'sender_name' => $user->name,
                'receiver_name' => $request->recipient,
                'sender_iban' => $senderAccount->iban,
                'receiver_iban' => $request->receiver_iban,
                'sender_bic' => $senderAccount->bic,
                'receiver_bic' => $request->receiver_bic,
                'amount' => $request->amount,
                'currency' => $request->devise,
                'type' => 'transfer',
                'status' => 'completed',
            ]);

            if (!$transaction) {
                Log::error('La transaction n’a pas pu être créée.');
                DB::rollBack();
                return back()->withErrors(['error' => 'Erreur lors de la création de la transaction.']);
            }

            Log::info('Transaction créée avec succès.', ['transaction_id' => $transaction->id]);

            DB::commit();

            return redirect()->route('saisir-code')->with('success', 'Transfert initié. Veuillez saisir le code.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du transfert', ['message' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Erreur lors du transfert : ' . $e->getMessage()]);
        }
    }

    public function afficherFormulaireCode()
    {
        return view('saisir-code');
    }
    public function afficherFormulaireCodeFin()
    {
        return view('saisir-code-fin');
    }
    public function afficherFormulaireCodeConf()
    {
        return view('saisir-code-conf');
    }

    public function afficherChargement(Request $request, string $etape)
    {
        // Définir le pourcentage en fonction de l'étape
        $pourcentage = 0;
        switch ($etape) {
            case 'initiation':
                $pourcentage = 30;
                break;
            case 'confirmation':
                $pourcentage = 60;
                break;
            case 'finalisation':
                $pourcentage = 90;
                break;
            default:
                $pourcentage = 0;
                break;
        }

        // Rediriger vers la page de saisie du code suivant après la progression
        if ($pourcentage < 100) {
            return view('chargement', compact('pourcentage', 'etape'));
        } else {
            return redirect()->route('page-de-succes')->with('success', 'Transfert terminé avec succès.');
        }
    }

    public function validerCode(Request $request, string $etape): RedirectResponse
    {
        // Validation du code saisi
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Récupérer le code généré par l'admin pour l'étape spécifique
        $codeAdmin = CodeTransfert::where('etape', $etape)
            ->where('code', $request->code)
            ->first();

        // Vérifier si le code saisi correspond au code généré par l'admin
        if (!$codeAdmin) {
            return redirect()->back()->with('error', 'Code invalide. Veuillez réessayer.');
        }

        // Mettre à jour l'étape dans la base de données
        $codeAdmin->update(['etape' => $etape]);

        // Rediriger vers la page de chargement
        return redirect()->route('chargement', ['etape' => $etape])->with('success', 'Code validé. Redirection vers la page de chargement...');
    }
}

