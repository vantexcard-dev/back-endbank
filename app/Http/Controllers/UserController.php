<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Card;
use App\Models\Account;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\PaymentLink;
use App\Models\PendingTransfer;

class UserController extends Controller
{
    //

  public function profile()
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

    $pendingTransfers = PendingTransfer::where('user_id', $user->id)
        ->where('validated', false)
        ->orderBy('created_at', 'desc')
        ->get();

    $link = PaymentLink::where('user_id', $user->id)->latest()->first();

    return response()->json([
        'user' => $user,
        'cartes' => $cartesCredit,
        'comptes' => $comptesBancaires,
        'prets' => $prets,
        'transactions' => $transactions,
        'pending_transfers' => $pendingTransfers,
        'dernier_lien_de_paiement' => $link,
    ]);
}
}
