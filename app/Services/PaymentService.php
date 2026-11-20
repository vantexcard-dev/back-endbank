<?php

namespace App\Services;

use App\Models\PaymentLink;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentRequestNotification;
class PaymentService
{
    public function generatePaymentLink(float $amount, int $userId, string $recipientEmail): array
    {
        $requester = User::findOrFail($userId);

        $paymentLink = PaymentLink::create([
            'user_id' => $userId,
            'amount' => $amount,
            'token' => Str::random(32),
            'status' => 'pending',
        ]);

        // Envoi de l'email
        Mail::to($recipientEmail)->send(
            new PaymentRequestNotification(
                amount: $amount,
                paymentUrl: route('payment.link', $paymentLink->token),
                requesterName: $requester->first_name . ' ' . $requester->last_name
            )
        );

        return [
            'payment_link' => route('payment.link', $paymentLink->token),
            'token' => $paymentLink->token,
            'amount' => $paymentLink->amount,
            'message' => 'Email envoyé avec succès'
        ];
    }

    public function processPayment(string $token, array $paymentData): array
    {
        $paymentLink = PaymentLink::where('token', $token)->firstOrFail();

        if ($paymentLink->isExpired()) {
            throw new \Exception('Le lien de paiement a expiré');
        }

        // Logique de traitement PayPal
        $accessToken = $this->getPayPalAccessToken();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->get("https://api.paypal.com/v2/checkout/orders/{$paymentData['orderID']}");

        if ($response->successful() && $response->json('status') === 'COMPLETED') {
            return $this->completePayment($paymentLink, $paymentData);
        }

        throw new \Exception('Paiement invalide');
    }

    private function completePayment(PaymentLink $paymentLink, array $paymentData): array
    {
        $paymentLink->markAsPaid();

        $receiverAccount = $paymentLink->user->accounts()->first();
        $receiverAccount->balance += $paymentLink->amount;
        $receiverAccount->save();

        Transaction::create([
            'sender_account_id' => null,
            'receiver_account_id' => $receiverAccount->id,
            'amount' => $paymentLink->amount,
            'currency' => 'EUR',
            'status' => 'completed',
            'metadata' => json_encode($paymentData)
        ]);

        return [
            'success' => true,
            'transaction_id' => $receiverAccount->id,
            'amount' => $paymentLink->amount
        ];
    }

    private function getPayPalAccessToken(): string
    {
        $response = Http::withBasicAuth(
            config('services.paypal.client_id'),
            config('services.paypal.secret')
        )->asForm()->post('https://api.paypal.com/v1/oauth2/token', [
                    'grant_type' => 'client_credentials',
                ]);

        if ($response->successful()) {
            return $response->json('access_token');
        }

        throw new \Exception('Impossible d\'obtenir un token d\'accès PayPal');
    }
}