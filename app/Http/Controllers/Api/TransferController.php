<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendingTransfer;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use App\Notifications\TransferVerificationCode;

class TransferController extends Controller
{
    // 1. Démarrer un transfert
    public function initiate(Request $request)
    {
        $request->validate([
            'recipient' => 'required|string|max:255',
            'receiver_iban' => 'required|string',
            'receiver_bic' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|max:10',
            'reason' => 'nullable|string|max:255',
        ]);

        $user = $request->user();

        if (!$user) {
            Log::warning('❌ Utilisateur non authentifié');
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $senderAccount = Account::where('user_id', $user->id)->first();

        if (!$senderAccount || $senderAccount->balance < $request->amount) {
            Log::warning('❌ Solde insuffisant ou compte non trouvé', [
                'user_id' => $user->id,
                'amount' => $request->amount
            ]);
            return response()->json(['error' => 'Insufficient balance or sender account not found'], 422);
        }

        $code = rand(100000, 999999);

        try {
            $pending = PendingTransfer::create([
                'user_id' => $user->id,
                'recipient' => $request->recipient,
                'receiver_iban' => $request->receiver_iban,
                'receiver_bic' => $request->receiver_bic,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'reason' => $request->reason,
                'verification_code' => $code,
                'expires_at' => now()->addMinutes(5),
            ]);

            Log::info('✅ Transfert en attente créé', [
                'user_id' => $user->id,
                'transfer_id' => $pending->id
            ]);

            // Envoyer l'email
            $user->notify(new TransferVerificationCode($code, $user->name));


            Log::info('📧 Code de vérification envoyé', [
                'email' => $user->email,
                'code' => $code
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erreur lors du transfert : ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Transfer failed: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Verification code sent to your email.',
            'transfer_id' => $pending->id
        ]);
    }

    // 2. Liste des transferts en attente
    public function pending(Request $request)
    {
        $user = $request->user();

        $transfers = PendingTransfer::where('user_id', $user->id)
            ->where('validated', false)
            ->orderByDesc('created_at')
            ->get([
                'id',
                'recipient',
                'receiver_iban',
                'receiver_bic',
                'amount',
                'currency',
                'reason',
                'created_at'
            ]);

        return response()->json([
            'pending_transfers' => $transfers
        ]);
    }

    // 3. Valider un transfert
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = $request->user();

        $pending = PendingTransfer::where('user_id', $user->id)
            ->where('verification_code', $request->code)
            ->where('validated', false)
            ->latest()
            ->first();

        if (!$pending || $pending->expires_at->isPast()) {
            Log::warning('❌ Code invalide ou expiré', [
                'user_id' => $user->id,
                'code' => $request->code
            ]);
            return response()->json(['error' => 'Invalid or expired code'], 422);
        }

        $senderAccount = Account::where('user_id', $user->id)->first();
        $receiverAccount = Account::where('iban', $pending->receiver_iban)->first();

        if (!$senderAccount || $senderAccount->balance < $pending->amount) {
            Log::warning('❌ Solde insuffisant pour finaliser', [
                'user_id' => $user->id,
                'amount' => $pending->amount
            ]);
            return response()->json(['error' => 'Insufficient balance'], 422);
        }

        DB::beginTransaction();
        try {
            $senderAccount->decrement('balance', $pending->amount);

            if ($receiverAccount) {
                $receiverAccount->increment('balance', $pending->amount);
            }

            $transaction = Transaction::create([
                'sender_account_id' => $senderAccount->id,
                'receiver_account_id' => $receiverAccount?->id,
                'sender_name' => $user->name,
                'receiver_name' => $pending->recipient,
                'sender_iban' => $senderAccount->iban,
                'receiver_iban' => $pending->receiver_iban,
                'sender_bic' => $senderAccount->bic,
                'receiver_bic' => $pending->receiver_bic,
                'amount' => $pending->amount,
                'currency' => $pending->currency,
                'type' => 'transfer',
                'status' => 'completed',
            ]);

            $pending->update(['validated' => true]);

            DB::commit();

            Log::info('✅ Transfert validé avec succès', [
                'transaction_id' => $transaction->id,
                'user_id' => $user->id
            ]);

            return response()->json([
                'message' => 'Transfer successful',
                'transaction_id' => $transaction->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Erreur lors de la validation du transfert', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Transfer failed: ' . $e->getMessage()], 500);
        }
    }
}
