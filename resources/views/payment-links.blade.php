<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Paiement</title>
    <!-- Intégration de Stripe -->
    <script src="https://js.stripe.com/v3/"></script>
    <!-- Ajoutez un token CSRF pour les requêtes AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        :root {
            --primary-color: #4a6bff;
            --primary-dark: #3a56d4;
            --secondary-color: #f8f9fa;
            --secondary-dark: rgb(0, 0, 0);
            --text-color: #333;
            --text-dark: #f0f0f0;
            --bg-color: #f5f7fa;
            --bg-dark: #1a202c;
            --card-bg: #ffffff;
            --card-bg-dark: rgb(0, 0, 0);
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --box-shadow-dark: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            transition: background-color 0.3s, color 0.3s;
        }

        body {
            font-family: 'Figtree', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--bg-color);
            padding: 0;
            margin: 0;
        }

        body.dark-mode {
            color: var(--text-dark);
            background-color: var(--bg-dark);
        }

        .container {
            max-width: 100%;
            padding: 20px;
            margin: 0 auto;
            min-height: 100vh;
        }

        @media (min-width: 768px) {
            .container {
                max-width: 600px;
                padding: 40px;
                margin: 20px auto;
                background-color: var(--card-bg);
                border-radius: var(--border-radius);
                box-shadow: var(--box-shadow);
                min-height: auto;
            }

            body.dark-mode .container {
                background-color: var(--card-bg-dark);
                box-shadow: var(--box-shadow-dark);
            }
        }

        /* Mode sombre automatique sur mobile */
        @media (max-width: 767px) {
            body {
                color: var(--text-dark);
                background-color: var(--bg-dark);
            }

            .container {
                background-color: var(--card-bg-dark);
            }
        }

        .payment-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .payment-header h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        body.dark-mode .payment-header h1 {
            color: var(--primary-dark);
        }

        .payment-details {
            background-color: var(--secondary-color);
            padding: 20px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
        }

        body.dark-mode .payment-details {
            background-color: var(--secondary-dark);
        }

        .payment-details p {
            margin-bottom: 10px;
            font-size: 16px;
        }

        .payment-amount {
            font-size: 28px;
            font-weight: 600;
            color: var(--primary-color);
            margin: 15px 0;
            text-align: center;
        }

        body.dark-mode .payment-amount {
            color: var(--primary-dark);
        }

        .payment-methods {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        #stripe-button-container {
            margin-bottom: 20px;
        }

        .stripe-button {
            background: linear-gradient(45deg, #6772E5 0%, #7795F8 100%);
            color: white;
            border: none;
            padding: 15px;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .stripe-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(103, 114, 229, 0.3);
        }

        .stripe-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-steinbank {
            background: linear-gradient(to right, #D95578, #994CA4, #6C4ED9, #0798F2, #FA5141, #060606);
            margin: 0;
            color: white;
            border: none;
            padding: 15px;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-steinbank:hover {
            background-color: var(--primary-dark);
        }

        .payment-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }

        body.dark-mode .payment-footer {
            color: #aaa;
        }

        /* Formulaires Stripe */
        .stripe-form {
            margin-bottom: 20px;
        }

        .stripe-element {
            background-color: var(--secondary-color);
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            padding: 15px;
            margin-bottom: 15px;
        }

        body.dark-mode .stripe-element {
            background-color: var(--secondary-dark);
            border-color: #444;
        }

        .StripeElement {
            padding: 10px;
            background-color: white;
            border-radius: 4px;
        }

        body.dark-mode .StripeElement {
            background-color: #2d3748;
            color: white;
        }

        .card-errors {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }

        /* Toggle Dark Mode (pour desktop) */
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--primary-color);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1000;
            box-shadow: var(--box-shadow);
        }

        body.dark-mode .theme-toggle {
            background: var(--primary-dark);
        }

        @media (max-width: 767px) {
            .theme-toggle {
                display: none;
                /* Cache le toggle sur mobile */
            }
        }

        /* Custom amount input */
        .custom-amount-container {
            margin: 20px 0;
        }

        .custom-amount-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: var(--border-radius);
            font-size: 16px;
            text-align: center;
            background-color: var(--secondary-color);
            color: var(--text-color);
            transition: border-color 0.3s;
        }

        body.dark-mode .custom-amount-input {
            background-color: var(--secondary-dark);
            border-color: #444;
            color: var(--text-dark);
        }

        .custom-amount-input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        body.dark-mode .custom-amount-input:focus {
            border-color: var(--primary-dark);
        }
    </style>
</head>

<body class="dark-mode">
    <!-- Bouton de bascule pour desktop -->
    <button class="theme-toggle" id="themeToggle">
        <i class="fas fa-moon" id="themeIcon"></i>
    </button>

    <div class="container py-5">
        <div class="payment-header text-center mb-4">
            <h1>Effectuer un paiement</h1>

            @if (!is_null($paymentLink->amount))
                <div class="payment-amount display-5 fw-bold text-primary">
                    {{ number_format($paymentLink->amount, 2) }} €
                </div>
            @else
                <div class="custom-amount-container">
                    <div class="payment-amount text-muted mb-2">
                        Montant à définir par le payeur
                    </div>
                    <input type="number" id="customAmount" class="custom-amount-input" placeholder="Montant en €" min="1" step="0.01">
                    <small class="text-muted d-block mt-2">Minimum 1€</small>
                </div>
            @endif
        </div>

        <div class="payment-details text-center mb-4">
            <p><i class="fas fa-user"></i> Destinataire : {{ $paymentLink->user->email }}</p>
            <p><i class="fas fa-lock"></i> Ce paiement est sécurisé par Stripe</p>
        </div>

        <div class="payment-methods text-center">
            <!-- Bouton Stripe -->
            <div id="stripe-button-container" class="stripe-form">
                <button id="stripe-payment-button" class="stripe-button">
                    <i class="fab fa-cc-stripe"></i> Payer avec Stripe
                </button>
                <div id="card-errors" class="card-errors" role="alert"></div>
            </div>

            <!-- Formulaire de carte bancaire -->
            <div id="stripe-payment-form" style="display: none;">
                <div class="stripe-element">
                    <div id="card-element"></div>
                </div>
                <button id="submit-payment" class="stripe-button">
                    <i class="fas fa-lock"></i> Payer maintenant
                </button>
                <button id="cancel-payment" class="btn-steinbank mt-2" style="background: #6c757d;">
                    <i class="fas fa-times"></i> Annuler
                </button>
            </div>

            <!-- Paiement via Vantexcard -->
            <form action="{{ route('payment.process', $paymentLink->token) }}" method="POST">
                @csrf
                <button type="submit" class="btn-steinbank">
                    <i class="fas fa-university"></i> Payer avec Vantexcard
                </button>
            </form>
        </div>

        <div class="payment-footer text-center mt-4">
            <p class="text-muted">
                <i class="fas fa-shield-alt"></i> Paiement 100% sécurisé
            </p>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Gestion du mode sombre/clair (pour desktop)
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const body = document.body;

            // Seulement pour desktop (le mode sombre est forcé sur mobile)
            if (window.innerWidth > 767) {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const savedTheme = localStorage.getItem('theme');

                if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                    body.classList.add('dark-mode');
                    themeIcon.classList.replace('fa-moon', 'fa-sun');
                } else {
                    body.classList.remove('dark-mode');
                    themeIcon.classList.replace('fa-sun', 'fa-moon');
                }

                themeToggle.addEventListener('click', () => {
                    body.classList.toggle('dark-mode');
                    const isDark = body.classList.contains('dark-mode');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    themeIcon.classList.toggle('fa-moon');
                    themeIcon.classList.toggle('fa-sun');
                });
            }

            // Variables globales
            let stripe = null;
            let elements = null;
            let cardElement = null;
            let paymentAmount = {{ $paymentLink->amount ?? 0 }};
            const customAmountInput = document.getElementById('customAmount');
            const stripePaymentButton = document.getElementById('stripe-payment-button');
            const stripePaymentForm = document.getElementById('stripe-payment-form');
            const submitPaymentButton = document.getElementById('submit-payment');
            const cancelPaymentButton = document.getElementById('cancel-payment');
            const cardErrors = document.getElementById('card-errors');

            // Initialiser Stripe avec votre clé publique
            stripe = Stripe('{{ env("STRIPE_KEY") }}'); // Remplacez par votre clé publique Stripe
            elements = stripe.elements();

            // Créer l'élément de carte
            cardElement = elements.create('card', {
                style: {
                    base: {
                        color: body.classList.contains('dark-mode') ? '#ffffff' : '#32325d',
                        fontFamily: "'Figtree', sans-serif",
                        fontSmoothing: 'antialiased',
                        fontSize: '16px',
                        '::placeholder': {
                            color: body.classList.contains('dark-mode') ? '#a0aec0' : '#aab7c4'
                        }
                    },
                    invalid: {
                        color: '#fa755a',
                        iconColor: '#fa755a'
                    }
                }
            });

            // Monter l'élément de carte
            cardElement.mount('#card-element');

            // Gérer les erreurs de carte
            cardElement.on('change', function(event) {
                if (event.error) {
                    cardErrors.textContent = event.error.message;
                } else {
                    cardErrors.textContent = '';
                }
            });

            // Gérer le montant personnalisé
            if (customAmountInput) {
                customAmountInput.addEventListener('input', function() {
                    const amount = parseFloat(this.value);
                    if (amount >= 1) {
                        paymentAmount = amount;
                        stripePaymentButton.disabled = false;
                    } else {
                        stripePaymentButton.disabled = true;
                    }
                });
            }

            // Afficher le formulaire de paiement Stripe
            stripePaymentButton.addEventListener('click', function() {
                if (paymentAmount <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Montant invalide',
                        text: 'Veuillez entrer un montant valide (minimum 1€)',
                        timer: 3000
                    });
                    return;
                }

                stripePaymentButton.style.display = 'none';
                stripePaymentForm.style.display = 'block';
            });

            // Annuler le paiement
            cancelPaymentButton.addEventListener('click', function() {
                stripePaymentForm.style.display = 'none';
                stripePaymentButton.style.display = 'flex';
            });

            // Soumettre le paiement
            submitPaymentButton.addEventListener('click', async function() {
                submitPaymentButton.disabled = true;
                submitPaymentButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement en cours...';

                // Créer un PaymentIntent côté serveur
                try {
                    const response = await fetch('{{ route("payment.create-intent", $paymentLink->token) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            amount: paymentAmount * 100, // Convertir en centimes
                            currency: 'eur'
                        })
                    });

                    const data = await response.json();

                    if (data.error) {
                        throw new Error(data.error);
                    }

                    // Confirmer le paiement avec Stripe
                    const result = await stripe.confirmCardPayment(data.clientSecret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                email: '{{ $paymentLink->user->email }}'
                            }
                        }
                    });

                    if (result.error) {
                        cardErrors.textContent = result.error.message;
                        submitPaymentButton.disabled = false;
                        submitPaymentButton.innerHTML = '<i class="fas fa-lock"></i> Payer maintenant';
                    } else {
                        // Paiement réussi
                        if (result.paymentIntent.status === 'succeeded') {
                            // Enregistrer le paiement
                            await fetch('{{ route("payment.validate", $paymentLink->token) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    payment_intent_id: result.paymentIntent.id,
                                    amount: paymentAmount,
                                    status: 'succeeded'
                                })
                            });

                            Swal.fire({
                                icon: 'success',
                                title: 'Paiement réussi!',
                                text: 'Votre paiement a été traité avec succès.',
                                confirmButtonText: 'Continuer',
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '{{ route("payment.success") }}';
                                }
                            });
                        }
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur est survenue lors du traitement du paiement',
                        timer: 3000
                    });
                    submitPaymentButton.disabled = false;
                    submitPaymentButton.innerHTML = '<i class="fas fa-lock"></i> Payer maintenant';
                }
            });

            // Gérer le changement de thème pour Stripe Elements
            themeToggle?.addEventListener('click', function() {
                setTimeout(() => {
                    if (cardElement) {
                        cardElement.update({
                            style: {
                                base: {
                                    color: body.classList.contains('dark-mode') ? '#ffffff' : '#32325d',
                                    '::placeholder': {
                                        color: body.classList.contains('dark-mode') ? '#a0aec0' : '#aab7c4'
                                    }
                                }
                            }
                        });
                    }
                }, 300);
            });
        });
    </script>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: "{{ session('error') }}",
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>