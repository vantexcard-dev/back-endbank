<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Paiement</title>
    <!-- Intégrez le SDK PayPal -->
    <script
        src="https://www.paypal.com/sdk/js?client-id=AYFnMvF6DLqq65EjvH7ZqSuXcb5XHiRfeOhVKTD4o9UVvss8IcIuUcTVu2pxv9NbFtQoMP97AaFyMATw&currency=EUR"></script>
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

        #paypal-button-container {
            margin-bottom: 20px;
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
                <div class="payment-amount text-muted">
                    Montant à définir par le payeur
                    <input type="number" id="customAmount" class="form-control" placeholder="Montant €">

                </div>
            @endif
        </div>

        <div class="payment-details text-center mb-4">
            <p><i class="fas fa-user"></i> Destinataire : {{ $paymentLink->user->email }}</p>
            <p><i class="fas fa-lock"></i> Ce paiement est sécurisé</p>
        </div>

        <div class="payment-methods text-center">
            <!-- Bouton PayPal -->
            <div id="paypal-button-container" class="mb-3"></div>

            <!-- Paiement via SteinBank -->
            <form action="{{ route('payment.process', $paymentLink->token) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-dark">
                    <i class="fas fa-university"></i> Payer avec Vantexcard
                </button>
            </form>
        </div>

        <div class="payment-footer text-center mt-4">
            <p class="text-muted">En continuant, vous acceptez nos conditions d'utilisation</p>
        </div>
    </div>


    <script>
        // Gestion du mode sombre/clair (pour desktop)
        document.addEventListener("DOMContentLoaded", function () {
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const body = document.body;

            // Seulement pour desktop (le mode sombre est forcé sur mobile)
            if (window.innerWidth > 767) {
                // Vérifier la préférence système ou le localStorage
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const savedTheme = localStorage.getItem('theme');

                if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                    body.classList.add('dark-mode');
                    themeIcon.classList.replace('fa-moon', 'fa-sun');
                } else {
                    body.classList.remove('dark-mode');
                    themeIcon.classList.replace('fa-sun', 'fa-moon');
                }

                // Basculer entre les modes
                themeToggle.addEventListener('click', () => {
                    body.classList.toggle('dark-mode');
                    const isDark = body.classList.contains('dark-mode');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    themeIcon.classList.toggle('fa-moon');
                    themeIcon.classList.toggle('fa-sun');
                });
            }

            // Configuration du bouton PayPal
            paypal.Buttons({
                style: {
                    layout: 'vertical',
                    color: 'blue',
                    shape: 'rect',
                    label: 'paypal',
                    height: 40
                },
                createOrder: function (data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '{{ $paymentLink->amount }}',
                                currency_code: 'EUR'
                            }
                        }]
                    });
                },
                onApprove: function (data, actions) {
                    return actions.order.capture().then(function (details) {
                        fetch('{{ route("payment.validate", $paymentLink->token) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                orderID: data.orderID,
                                payerID: data.payerID,
                                details: details
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    window.location.href = '{{ route("payment.success") }}';
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Erreur',
                                        text: 'Erreur lors de la validation du paiement',
                                        timer: 3000
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Erreur :', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur',
                                    text: 'Une erreur est survenue lors du traitement du paiement',
                                    timer: 3000
                                });
                            });
                    });
                },
                onCancel: function () {
                    Swal.fire({
                        icon: 'info',
                        title: 'Paiement annulé',
                        text: 'Vous avez annulé le paiement',
                        timer: 3000
                    });
                },
                onError: function (err) {
                    console.error('Erreur lors de la transaction : ', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur est survenue lors du traitement du paiement',
                        timer: 3000
                    });
                }
            }).render('#paypal-button-container');
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