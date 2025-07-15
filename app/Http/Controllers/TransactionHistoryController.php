<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Account;

class TransactionHistoryController extends Controller
{
    /**
     * Affiche l'historique des transactions de l'utilisateur connecté.
     */
    public function index()
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Récupérer tous les comptes bancaires de l'utilisateur
        $comptesBancaires = Account::where('user_id', $user->id)->get();

        // Vérifier qu'il a au moins un compte bancaire
        if ($comptesBancaires->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'Aucun compte bancaire trouvé.');
        }

        // Extraire tous les IBANs des comptes
        $ibans = $comptesBancaires->pluck('iban')->toArray();

        // Récupérer toutes les transactions où l'IBAN est soit expéditeur soit destinataire
        $transactions = Transaction::whereIn('sender_iban', $ibans)
            ->orWhereIn('receiver_iban', $ibans)
            ->orderBy('created_at', 'desc')
            ->get();

        // Retourner la vue avec les transactions
        return view('wallet', compact('transactions', 'comptesBancaires'));
    }
}
