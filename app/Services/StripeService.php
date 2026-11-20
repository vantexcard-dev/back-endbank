<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Créer un PaymentIntent Stripe avec gestion d'expiration
     */
    public function createPaymentIntent(int $amount, string $currency, array $metadata = []): array
    {
        try {
            $paymentMethodType = $metadata['payment_method_type'] ?? 'card';

            // Configuration de base avec expiration étendue
            $paymentIntentData = [
                'amount' => $amount,
                'currency' => $currency,
                'metadata' => $metadata,
                'capture_method' => 'automatic',
                // Ajouter un timeout plus long pour éviter les expirations prématurées
                'payment_method_options' => [
                    'card' => [
                        'request_three_d_secure' => 'automatic',
                    ],
                ],
            ];

            // Configuration selon le type de paiement
            if ($paymentMethodType === 'ideal') {
                $paymentIntentData['payment_method_types'] = ['ideal'];
                // iDEAL peut nécessiter un traitement différent
                $paymentIntentData['capture_method'] = 'automatic';
            } else {
                $paymentIntentData['automatic_payment_methods'] = [
                    'enabled' => true,
                ];
            }

            Log::info('🔄 Creating Stripe PaymentIntent', [
                'amount' => $amount,
                'currency' => $currency,
                'payment_method_type' => $paymentMethodType,
                'amount_in_euros' => ($amount / 100) . ' EUR'
            ]);

            $paymentIntent = PaymentIntent::create($paymentIntentData);

            Log::info('✅ PaymentIntent created successfully', [
                'payment_intent_id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
                'client_secret' => substr($paymentIntent->client_secret, 0, 20) . '...', // Log partiel pour sécurité
                'amount' => $paymentIntent->amount,
                'currency' => $paymentIntent->currency
            ]);

            return [
                'success' => true,
                'payment_intent_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
                'status' => $paymentIntent->status,
                'amount' => $paymentIntent->amount,
                'currency' => $paymentIntent->currency,
            ];

        } catch (ApiErrorException $e) {
            Log::error('❌ Stripe PaymentIntent Creation Error: ' . $e->getMessage(), [
                'amount' => $amount,
                'currency' => $currency,
                'metadata' => $metadata
            ]);

            return [
                'success' => false,
                'error' => $this->formatErrorMessage($e),
            ];
        }
    }

    /**
     * Confirmer un paiement Stripe - Version optimisée
     */
    public function confirmPayment(string $paymentIntentId): array
    {
        try {
            Log::info('🔍 Retrieving PaymentIntent for confirmation', [
                'payment_intent_id' => $paymentIntentId
            ]);

            // Récupérer le PaymentIntent
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            Log::info('📊 PaymentIntent status check', [
                'payment_intent_id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
                'amount' => $paymentIntent->amount,
                'currency' => $paymentIntent->currency,
                'last_payment_error' => $paymentIntent->last_payment_error ? $paymentIntent->last_payment_error->message : null
            ]);

            // Si le paiement est déjà réussi
            if ($paymentIntent->status === 'succeeded') {
                Log::info('✅ Payment already succeeded');
                return [
                    'success' => true,
                    'payment_intent' => $paymentIntent,
                    'status' => 'succeeded',
                    'message' => 'Paiement déjà traité avec succès'
                ];
            }

            // Si le paiement a échoué
            if ($paymentIntent->status === 'canceled' || $paymentIntent->status === 'requires_payment_method') {
                Log::warning('❌ Payment failed or canceled', [
                    'status' => $paymentIntent->status,
                    'last_error' => $paymentIntent->last_payment_error
                ]);
                
                return [
                    'success' => false,
                    'error' => $paymentIntent->last_payment_error ? 
                              $paymentIntent->last_payment_error->message : 
                              'Le paiement a échoué. Statut: ' . $paymentIntent->status,
                    'status' => $paymentIntent->status
                ];
            }

            // Confirmer si nécessaire
            if (in_array($paymentIntent->status, ['requires_confirmation', 'requires_action'])) {
                Log::info('🔄 Confirming PaymentIntent', [
                    'current_status' => $paymentIntent->status
                ]);
                
                $paymentIntent->confirm();

                // Recharger le PaymentIntent pour avoir le statut actualisé
                $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

                Log::info('🔄 PaymentIntent after confirmation', [
                    'new_status' => $paymentIntent->status
                ]);
            }

            // Vérifier le statut final
            if ($paymentIntent->status === 'succeeded') {
                Log::info('🎉 Payment confirmed successfully');
                return [
                    'success' => true,
                    'payment_intent' => $paymentIntent,
                    'status' => 'succeeded',
                    'message' => 'Paiement confirmé avec succès'
                ];
            } elseif ($paymentIntent->status === 'processing') {
                Log::info('⏳ Payment processing');
                return [
                    'success' => true,
                    'payment_intent' => $paymentIntent,
                    'status' => 'processing',
                    'message' => 'Paiement en cours de traitement'
                ];
            } elseif ($paymentIntent->status === 'requires_action') {
                Log::info('🔐 Payment requires action (3DS)');
                return [
                    'success' => true,
                    'payment_intent' => $paymentIntent,
                    'status' => 'requires_action',
                    'message' => 'Action supplémentaire requise'
                ];
            }

            // Statut inattendu
            Log::warning('⚠️ Unexpected payment status', [
                'status' => $paymentIntent->status
            ]);

            return [
                'success' => false,
                'error' => 'Statut de paiement inattendu: ' . $paymentIntent->status,
                'status' => $paymentIntent->status
            ];

        } catch (ApiErrorException $e) {
            Log::error('❌ Stripe Payment Confirmation Error: ' . $e->getMessage(), [
                'payment_intent_id' => $paymentIntentId,
                'error_type' => $e->getError()->type ?? 'unknown',
                'error_code' => $e->getError()->code ?? 'unknown'
            ]);

            return [
                'success' => false,
                'error' => $this->formatErrorMessage($e),
                'raw_error' => $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer un PaymentIntent avec gestion d'erreur améliorée
     */
    public function retrievePaymentIntent(string $paymentIntentId): array
    {
        try {
            Log::info('🔍 Retrieving PaymentIntent', ['payment_intent_id' => $paymentIntentId]);

            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            Log::info('✅ PaymentIntent retrieved', [
                'id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
                'amount' => $paymentIntent->amount,
                'currency' => $paymentIntent->currency,
                'created' => $paymentIntent->created
            ]);

            return [
                'success' => true,
                'payment_intent' => $paymentIntent,
                'status' => $paymentIntent->status,
            ];

        } catch (ApiErrorException $e) {
            Log::error('❌ Stripe PaymentIntent Retrieval Error: ' . $e->getMessage(), [
                'payment_intent_id' => $paymentIntentId
            ]);

            // Vérifier si c'est une erreur "not found"
            if (str_contains($e->getMessage(), 'No such payment_intent')) {
                return [
                    'success' => false,
                    'error' => 'Session de paiement expirée ou introuvable',
                    'code' => 'payment_intent_expired'
                ];
            }

            return [
                'success' => false,
                'error' => $this->formatErrorMessage($e),
                'code' => 'retrieval_error'
            ];
        }
    }

    /**
     * Vérifier le statut d'un PaymentIntent
     */
    public function getPaymentStatus(string $paymentIntentId): array
    {
        $result = $this->retrievePaymentIntent($paymentIntentId);
        
        if (!$result['success']) {
            return $result;
        }

        $paymentIntent = $result['payment_intent'];

        return [
            'success' => true,
            'status' => $paymentIntent->status,
            'payment_intent' => $paymentIntent,
            'amount' => $paymentIntent->amount,
            'currency' => $paymentIntent->currency,
            'created' => $paymentIntent->created,
            'last_payment_error' => $paymentIntent->last_payment_error
        ];
    }

    /**
     * Annuler un PaymentIntent expiré ou invalide
     */
    public function cancelPaymentIntent(string $paymentIntentId): array
    {
        try {
            Log::info('🗑️ Canceling PaymentIntent', ['payment_intent_id' => $paymentIntentId]);

            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            // Annuler seulement si le statut le permet
            if (in_array($paymentIntent->status, ['requires_payment_method', 'requires_confirmation', 'requires_action'])) {
                $canceledIntent = $paymentIntent->cancel();
                
                Log::info('✅ PaymentIntent canceled', [
                    'payment_intent_id' => $canceledIntent->id,
                    'status' => $canceledIntent->status
                ]);

                return [
                    'success' => true,
                    'payment_intent' => $canceledIntent,
                    'message' => 'PaymentIntent annulé'
                ];
            }

            Log::info('ℹ️ PaymentIntent not canceled - invalid status', [
                'status' => $paymentIntent->status
            ]);

            return [
                'success' => false,
                'error' => 'Impossible d\'annuler le PaymentIntent avec le statut: ' . $paymentIntent->status
            ];

        } catch (ApiErrorException $e) {
            Log::error('❌ Stripe Cancel PaymentIntent Error: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $this->formatErrorMessage($e)
            ];
        }
    }

    /**
     * Formater les messages d'erreur pour l'utilisateur
     */
    private function formatErrorMessage(ApiErrorException $e): string
    {
        $error = $e->getError();
        $message = $e->getMessage();

        // Messages d'erreur courants et leurs traductions
        $errorMappings = [
            'resource_missing' => 'Session de paiement expirée. Veuillez actualiser la page et réessayer.',
            'payment_intent_unexpected_state' => 'État de paiement invalide. Veuillez réessayer.',
            'card_error' => 'Erreur de carte bancaire: ' . ($error->message ?? $message),
            'invalid_request_error' => 'Erreur de requête: ' . ($error->message ?? $message),
        ];

        $errorType = $error->type ?? 'unknown';
        $errorCode = $error->code ?? 'unknown';

        // Retourner le message formaté ou le message original
        return $errorMappings[$errorType] ?? $errorMappings[$errorCode] ?? $message;
    }

    /**
     * Vérifier si un PaymentIntent est encore valide
     */
    public function isValidPaymentIntent(string $paymentIntentId): array
    {
        $result = $this->retrievePaymentIntent($paymentIntentId);
        
        if (!$result['success']) {
            return $result;
        }

        $paymentIntent = $result['payment_intent'];
        
        // Vérifier si le PaymentIntent a été créé récemment (moins de 30 minutes)
        $createdTime = $paymentIntent->created;
        $currentTime = time();
        $timeDiff = $currentTime - $createdTime;
        $isRecent = $timeDiff < 1800; // 30 minutes

        $isValidStatus = in_array($paymentIntent->status, [
            'requires_payment_method',
            'requires_confirmation', 
            'requires_action',
            'processing'
        ]);

        return [
            'success' => true,
            'is_valid' => $isValidStatus && $isRecent,
            'status' => $paymentIntent->status,
            'created_ago' => $timeDiff,
            'is_recent' => $isRecent,
            'payment_intent' => $paymentIntent
        ];
    }
}