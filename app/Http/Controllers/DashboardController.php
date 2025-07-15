<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Account;
use App\Models\Loan;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PaymentLink;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord de l'utilisateur.
     */
    public function index()
    {
        $user = Auth::user();

        $cartesCredit = Card::where('user_id', $user->id)->get();
        $comptesBancaires = Account::where('user_id', $user->id)->get();
        $prets = Loan::where('user_id', $user->id)->get();

        $ibans = $comptesBancaires->pluck('iban')->toArray();

        $transactions = Transaction::whereIn('sender_iban', $ibans)
            ->orWhereIn('receiver_iban', $ibans)
            ->orderBy('created_at', 'desc')
            ->get();

        $link = PaymentLink::where('user_id', $user->id)->latest()->first(); // facultatif

        return view('dashboard', compact(
            'user',
            'cartesCredit',
            'comptesBancaires',
            'prets',
            'transactions',
            'link'

        ));
    }

    /**
     * Soumet une demande de prêt.
     */
    public function submitLoanRequest(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'repayment_years' => 'required|integer|min:1',
            'payment_date' => 'required|date|after:today',
        ]);

        $totalInterest = ($validated['amount'] * $validated['interest_rate'] / 100) * $validated['repayment_years'];

        Loan::create([
            'user_id' => auth()->id(),
            'amount' => $validated['amount'],
            'interest_rate' => $validated['interest_rate'],
            'total_interest' => $totalInterest,
            'repayment_years' => $validated['repayment_years'],
            'payment_date' => Carbon::parse($validated['payment_date']),
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Votre demande de prêt a été soumise.');
    }
    public function show($token)
    {
        $link = PaymentLink::where('token', $token)->firstOrFail();

        if ($link->isExpired()) {
            abort(403, 'Ce lien de paiement est expiré.');
        }

        // ✅ Transmet correctement la variable $link à la vue
        return view('dashboard', compact('link'));
    }

}
