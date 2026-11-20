<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use App\Services\StripeService;
use App\Services\PdfService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $stripeService;
    protected $pdfService;

    public function __construct(StripeService $stripeService, PdfService $pdfService)
    {
        $this->stripeService = $stripeService;
        $this->pdfService = $pdfService;
    }

    /**
     * Création d'une intention de paiement
     */
public function createPaymentIntent(PaymentRequest $request): JsonResponse
{
    try {
        Log::info('=== DÉBUT CRÉATION PAYMENT INTENT ===', $request->all());

        $validated = $request->validated();

        // Convertir le montant en centimes (Stripe attend un entier)
        $amountInCents = (int)round($validated['amount'] * 100);
        
        // Vérifier que le montant est valide
        if ($amountInCents < 50) { // Minimum 0.50€
            return response()->json([
                'success' => false,
                'error' => 'Le montant minimum est de 0.50€'
            ], 400);
        }

        // Récupérer le type de méthode de paiement
        $paymentMethodType = $validated['payment_method_type'] ?? 'card';

        Log::info('Données pour PaymentIntent', [
            'amount_in_cents' => $amountInCents,
            'payment_method_type' => $paymentMethodType,
            'original_amount' => $validated['amount'],
            'amount_in_euros' => $validated['amount'] . ' EUR'
        ]);

        // VÉRIFICATION RÉUTILISATION PAYMENT INTENT
        $existingPaymentIntentId = $request->input('existing_payment_intent_id');
        $canReuse = false;
        
        if ($existingPaymentIntentId) {
            $validityCheck = $this->stripeService->isValidPaymentIntent($existingPaymentIntentId);
            
            if ($validityCheck['success'] && $validityCheck['is_valid']) {
                Log::info('♻️ PaymentIntent existant valide - réutilisation', [
                    'payment_intent_id' => $existingPaymentIntentId,
                    'status' => $validityCheck['status'],
                    'created_ago' => $validityCheck['created_ago'] . ' seconds'
                ]);
                $canReuse = true;
            } else {
                Log::info('🔄 PaymentIntent existant invalide - création nouveau', [
                    'payment_intent_id' => $existingPaymentIntentId,
                    'is_valid' => $validityCheck['is_valid'] ?? false,
                    'status' => $validityCheck['status'] ?? 'unknown',
                    'error' => $validityCheck['error'] ?? 'none'
                ]);
                
                // Annuler l'ancien PaymentIntent invalide
                if (isset($validityCheck['payment_intent'])) {
                    $this->stripeService->cancelPaymentIntent($existingPaymentIntentId);
                }
            }
        }

        // RÉUTILISATION ou CRÉATION
        if ($canReuse) {
            // Récupérer le PaymentIntent existant
            $retrieveResult = $this->stripeService->retrievePaymentIntent($existingPaymentIntentId);
            
            if ($retrieveResult['success']) {
                $paymentIntent = $retrieveResult['payment_intent'];
                
                // Mettre à jour l'enregistrement local existant ou en créer un nouveau
                $payment = Payment::updateOrCreate(
                    ['payment_id' => $existingPaymentIntentId],
                    [
                        'customer_email' => $validated['email'],
                        'amount' => $validated['amount'],
                        'original_amount' => $validated['original_amount'] ?? $validated['amount'],
                        'fees' => $validated['fees'] ?? 0,
                        'description' => $validated['description'] ?? 'Paiement VantexCard',
                        'status' => 'pending',
                        'stripe_payment_intent_id' => $existingPaymentIntentId,
                        'billing_details' => $validated['billing_details'] ?? [],
                        'payment_method_type' => $paymentMethodType,
                    ]
                );

                Log::info('=== PAYMENT INTENT RÉUTILISÉ AVEC SUCCÈS ===', [
                    'payment_id' => $payment->payment_id,
                    'stripe_payment_intent_id' => $existingPaymentIntentId,
                    'status' => $paymentIntent->status,
                    'client_secret_present' => !empty($paymentIntent->client_secret)
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Session de paiement réutilisée avec succès',
                    'client_secret' => $paymentIntent->client_secret,
                    'payment_id' => $payment->payment_id,
                    'payment_intent_id' => $existingPaymentIntentId,
                    'status' => $paymentIntent->status,
                    'reused' => true,
                    'existing_intent_reused' => true
                ]);
            }
        }

        // CRÉATION D'UN NOUVEAU PAYMENT INTENT
        Log::info('🆕 Création nouveau PaymentIntent', [
            'reason' => $canReuse ? 'reuse_failed' : 'no_existing_intent',
            'existing_intent_id' => $existingPaymentIntentId
        ]);

        $result = $this->stripeService->createPaymentIntent(
            $amountInCents,
            'eur',
            [
                'payment_id' => 'pay_' . Str::random(16),
                'description' => $validated['description'] ?? 'Paiement VantexCard',
                'email' => $validated['email'],
                'payment_method_type' => $paymentMethodType,
                'original_amount' => $validated['original_amount'] ?? $validated['amount'],
                'fees' => $validated['fees'] ?? 0,
                'billing_name' => $validated['billing_details']['name'] ?? '',
                'billing_country' => $validated['billing_details']['address']['country'] ?? '',
            ]
        );

        if (!$result['success']) {
            Log::error('❌ Échec création PaymentIntent', [
                'error' => $result['error'],
                'raw_error' => $result['raw_error'] ?? 'none'
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $result['error'],
                'error_type' => 'stripe_creation_failed'
            ], 400);
        }

        // Créer un enregistrement local du paiement
        $payment = Payment::create([
            'payment_id' => $result['payment_intent_id'],
            'customer_email' => $validated['email'],
            'amount' => $validated['amount'],
            'original_amount' => $validated['original_amount'] ?? $validated['amount'],
            'fees' => $validated['fees'] ?? 0,
            'description' => $validated['description'] ?? 'Paiement VantexCard',
            'status' => 'pending',
            'stripe_payment_intent_id' => $result['payment_intent_id'],
            'billing_details' => $validated['billing_details'] ?? [],
            'payment_method_type' => $paymentMethodType,
        ]);

        Log::info('=== NOUVEAU PAYMENT INTENT CRÉÉ AVEC SUCCÈS ===', [
            'payment_id' => $payment->payment_id,
            'stripe_payment_intent_id' => $result['payment_intent_id'],
            'status' => $result['status'] ?? 'unknown',
            'amount_stripe' => $result['amount'] ?? 'unknown',
            'currency' => $result['currency'] ?? 'unknown',
            'client_secret_present' => !empty($result['client_secret'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nouvelle intention de paiement créée avec succès',
            'client_secret' => $result['client_secret'],
            'payment_id' => $payment->payment_id,
            'payment_intent_id' => $result['payment_intent_id'],
            'status' => $result['status'] ?? 'unknown',
            'reused' => false,
            'existing_intent_reused' => false
        ]);

    } catch (\Exception $e) {
        Log::error('💥 Payment Intent Creation Error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->except(['billing_details', 'email']) // Masquer les données sensibles
        ]);

        return response()->json([
            'success' => false,
            'error' => 'Erreur interne lors de la création du paiement: ' . $e->getMessage(),
            'error_type' => 'internal_error'
        ], 500);
    }
}

    /**
     * Confirmation du paiement Stripe - CORRIGÉ
     */
    public function confirmPayment(Request $request): JsonResponse
    {
        try {
            Log::info('=== DÉBUT CONFIRMATION PAIEMENT ===', $request->all());

            $paymentId = $request->input('payment_id');
            $paymentIntentId = $request->input('payment_intent_id');

            if (!$paymentId || !$paymentIntentId) {
                return response()->json([
                    'success' => false,
                    'error' => 'Données de paiement manquantes'
                ], 400);
            }

            Log::info('Données confirmation', [
                'payment_id' => $paymentId,
                'payment_intent_id' => $paymentIntentId
            ]);

            // Trouver le paiement par l'ID Stripe (puisque nous utilisons le même ID)
            $payment = Payment::where('payment_id', $paymentIntentId)->first();

            if (!$payment) {
                Log::warning('Paiement non trouvé', ['payment_id' => $paymentId]);
                return response()->json([
                    'success' => false,
                    'error' => 'Paiement non trouvé'
                ], 404);
            }

            // Vérifier le statut actuel du paiement
            $statusResult = $this->stripeService->getPaymentStatus($paymentIntentId);

            if (!$statusResult['success']) {
                Log::error('Erreur vérification statut', ['error' => $statusResult['error']]);
                return response()->json([
                    'success' => false,
                    'error' => 'Impossible de vérifier le statut du paiement: ' . $statusResult['error']
                ], 400);
            }

            $currentStatus = $statusResult['status'];
            Log::info('Statut actuel du paiement', ['status' => $currentStatus]);

            // Si le paiement est déjà réussi
            if ($currentStatus === 'succeeded') {
                Log::info('Paiement déjà réussi, mise à jour du statut');
                $payment->update(['status' => 'completed']);

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement déjà confirmé',
                    'status' => 'succeeded',
                    'payment_id' => $payment->payment_id,
                ]);
            }

            // Confirmer le paiement si nécessaire
            if (in_array($currentStatus, ['requires_confirmation', 'requires_action', 'processing'])) {
                Log::info('Confirmation du paiement nécessaire', ['status' => $currentStatus]);
                
                $result = $this->stripeService->confirmPayment($paymentIntentId);

                if (!$result['success']) {
                    $payment->update(['status' => 'failed']);
                    return response()->json([
                        'success' => false,
                        'error' => $result['error']
                    ], 400);
                }

                $paymentIntent = $result['payment_intent'];
                $newStatus = $paymentIntent->status;

                Log::info('Statut après confirmation', ['new_status' => $newStatus]);

                // Mettre à jour le statut local
                $payment->update(['status' => $newStatus]);

                if ($newStatus === 'succeeded') {
                    // Générer le reçu PDF
                    $pdfResult = $this->pdfService->generateReceipt($payment);

                    if ($pdfResult['success']) {
                        $payment->update(['pdf_receipt_path' => $pdfResult['file_path']]);
                    }

                    return response()->json([
                        'success' => true,
                        'message' => 'Paiement confirmé avec succès',
                        'status' => 'succeeded',
                        'payment_id' => $payment->payment_id,
                    ]);
                } else {
                    return response()->json([
                        'success' => true,
                        'message' => 'Paiement en cours de traitement',
                        'status' => $newStatus,
                        'payment_id' => $payment->payment_id,
                    ]);
                }
            }

            // Si le statut est inattendu
            Log::warning('Statut de paiement inattendu', ['status' => $currentStatus]);
            return response()->json([
                'success' => false,
                'error' => 'Statut de paiement inattendu: ' . $currentStatus
            ], 400);

        } catch (\Exception $e) {
            Log::error('Payment Confirmation Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la confirmation du paiement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Téléchargement du reçu PDF
     */
    public function downloadReceipt($paymentId)
    {
        try {
            Log::info('=== DÉBUT TÉLÉCHARGEMENT RECEIPT ===', ['payment_id' => $paymentId]);

            $payment = Payment::where('payment_id', $paymentId)->firstOrFail();

            if (!$payment->pdf_receipt_path) {
                abort(404, 'Reçu non trouvé');
            }

            $filePath = storage_path('app/public/receipts' . $payment->pdf_receipt_path);

            if (!file_exists($filePath)) {
                abort(404, 'Fichier reçu non trouvé');
            }

            Log::info('=== TÉLÉCHARGEMENT RÉUSSI ===');

            return response()->download(
                $filePath,
                'reçu-paiement-' . $payment->payment_id . '.pdf'
            );
        } catch (\Exception $e) {
            Log::error('Download Receipt Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            abort(404, 'Reçu non trouvé');
        }
    }
}