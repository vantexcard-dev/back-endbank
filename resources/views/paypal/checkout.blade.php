<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>Paiement - VantexCard</title>

  <!-- SDK Stripe -->
  <script src="https://js.stripe.com/v3/"></script>

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <!-- Icônes & Alertes -->
  <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <style>
    :root {
      --primary-color: #4a6bff;
      --primary-dark: #3a56d4;
      --stripe-color: #6772E5;
      --stripe-dark: #5469d4;
      --bg-light: #f5f7fa;
      --bg-dark: #1a202c;
      --text-light: #333;
      --text-dark: #f0f0f0;
      --card-bg-light: #ffffff;
      --card-bg-dark: #121826;
      --border-radius: 8px;
      --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      --box-shadow-dark: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      transition: background-color 0.3s, color 0.3s;
      font-family: 'Figtree', sans-serif;
    }

    body {
      background-color: var(--bg-light);
      color: var(--text-light);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    body.dark-mode {
      background-color: var(--bg-dark);
      color: var(--text-dark);
    }

    .container {
      background-color: var(--card-bg-light);
      padding: 40px 30px;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      width: 100%;
      max-width: 500px;
      text-align: center;
    }

    body.dark-mode .container {
      background-color: var(--card-bg-dark);
      box-shadow: var(--box-shadow-dark);
    }

    h1 {
      color: var(--primary-color);
      margin-bottom: 10px;
      font-weight: 600;
      font-size: 28px;
    }

    body.dark-mode h1 {
      color: var(--primary-dark);
    }

    .amount-display {
      font-size: 36px;
      font-weight: 700;
      margin: 20px 0;
      color: var(--primary-color);
    }

    body.dark-mode .amount-display {
      color: var(--primary-dark);
    }

    .payment-method {
      margin: 30px 0;
    }

    .stripe-button {
      background: linear-gradient(45deg, var(--stripe-color) 0%, #7795F8 100%);
      color: white;
      border: none;
      padding: 15px 20px;
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
      margin-bottom: 20px;
    }

    .stripe-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(103, 114, 229, 0.3);
    }

    .stripe-button:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    /* Formulaires Stripe */
    .stripe-form {
      display: none;
      margin-top: 20px;
    }

    .stripe-form.active {
      display: block;
    }

    .stripe-element-container {
      background-color: var(--bg-light);
      border: 1px solid #e0e0e0;
      border-radius: var(--border-radius);
      padding: 15px;
      margin-bottom: 15px;
    }

    body.dark-mode .stripe-element-container {
      background-color: #2d3748;
      border-color: #4a5568;
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
      margin: 10px 0;
      min-height: 20px;
    }

    .payment-actions {
      display: flex;
      gap: 10px;
      margin-top: 15px;
    }

    .pay-button {
      background-color: var(--primary-color);
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: var(--border-radius);
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      flex: 1;
      transition: background-color 0.3s;
    }

    body.dark-mode .pay-button {
      background-color: var(--primary-dark);
    }

    .pay-button:hover {
      opacity: 0.9;
    }

    .pay-button:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    .cancel-button {
      background-color: #6c757d;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: var(--border-radius);
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      flex: 1;
      transition: background-color 0.3s;
    }

    .cancel-button:hover {
      opacity: 0.9;
    }

    .payment-info {
      margin-top: 25px;
      padding: 15px;
      background-color: rgba(103, 114, 229, 0.1);
      border-radius: var(--border-radius);
      font-size: 14px;
    }

    body.dark-mode .payment-info {
      background-color: rgba(103, 114, 229, 0.2);
    }

    /* Toggle thème */
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
      box-shadow: var(--box-shadow);
      z-index: 1000;
    }

    body.dark-mode .theme-toggle {
      background: var(--primary-dark);
    }

    /* Responsive */
    @media (max-width: 480px) {
      .container {
        padding: 30px 20px;
      }
      
      .payment-actions {
        flex-direction: column;
      }
      
      .amount-display {
        font-size: 30px;
      }
    }
  </style>
</head>

<body class="dark-mode">
  <button class="theme-toggle" id="themeToggle" aria-label="Changer le thème">
    <i class="fas fa-moon" id="themeIcon"></i>
  </button>

  <div class="container">
    <h1>Effectuer un paiement</h1>
    
    <div class="amount-display">
      {{ number_format($amount, 2) }} €
    </div>
    
    <div class="payment-method">
      <button id="stripe-payment-button" class="stripe-button">
        <i class="fab fa-cc-stripe"></i> Payer avec Stripe
      </button>
      
      <div id="stripe-payment-form" class="stripe-form">
        <div class="stripe-element-container">
          <div id="card-element"></div>
        </div>
        
        <div id="card-errors" class="card-errors" role="alert"></div>
        
        <div class="payment-actions">
          <button id="submit-payment" class="pay-button" disabled>
            <i class="fas fa-lock"></i> Payer {{ number_format($amount, 2) }} €
          </button>
          <button id="cancel-payment" class="cancel-button">
            <i class="fas fa-times"></i> Annuler
          </button>
        </div>
      </div>
    </div>
    
    <div class="payment-info">
      <i class="fas fa-shield-alt"></i> Paiement 100% sécurisé par Stripe
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Variables
      const themeToggle = document.getElementById('themeToggle');
      const themeIcon = document.getElementById('themeIcon');
      const body = document.body;
      const stripePaymentButton = document.getElementById('stripe-payment-button');
      const stripePaymentForm = document.getElementById('stripe-payment-form');
      const submitPaymentButton = document.getElementById('submit-payment');
      const cancelPaymentButton = document.getElementById('cancel-payment');
      const cardErrors = document.getElementById('card-errors');
      
      // Montant du paiement
      const paymentAmount = {{ $amount }};
      
      // Toggle mode sombre/clair
      function updateThemeIcon() {
        if (body.classList.contains('dark-mode')) {
          themeIcon.classList.replace('fa-moon', 'fa-sun');
        } else {
          themeIcon.classList.replace('fa-sun', 'fa-moon');
        }
      }

      themeToggle.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        updateThemeIcon();
        updateStripeElementTheme();
      });

      updateThemeIcon();

      // Initialiser Stripe
      const stripe = Stripe('{{ env("STRIPE_PUBLIC_KEY") }}');
      const elements = stripe.elements();
      
      // Créer l'élément de carte
      const cardElement = elements.create('card', {
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

      // Mettre à jour le thème des éléments Stripe
      function updateStripeElementTheme() {
        setTimeout(() => {
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
        }, 300);
      }

      // Gérer les erreurs de carte
      cardElement.on('change', function(event) {
        if (event.error) {
          cardErrors.textContent = event.error.message;
          submitPaymentButton.disabled = true;
        } else {
          cardErrors.textContent = '';
          submitPaymentButton.disabled = false;
        }
      });

      // Afficher le formulaire Stripe
      stripePaymentButton.addEventListener('click', function() {
        stripePaymentButton.style.display = 'none';
        stripePaymentForm.classList.add('active');
      });

      // Annuler le paiement
      cancelPaymentButton.addEventListener('click', function() {
        stripePaymentForm.classList.remove('active');
        stripePaymentButton.style.display = 'flex';
        cardErrors.textContent = '';
        submitPaymentButton.disabled = true;
      });

      // Soumettre le paiement
      submitPaymentButton.addEventListener('click', async function() {
        submitPaymentButton.disabled = true;
        submitPaymentButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
        
        try {
          // 1. Créer un PaymentIntent
          const response = await fetch('{{ route("stripe.create-intent") }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
              amount: paymentAmount,
              currency: 'eur',
              payment_method: 'stripe'
            })
          });

          const data = await response.json();

          if (data.error) {
            throw new Error(data.error);
          }

          // 2. Confirmer le paiement
          const result = await stripe.confirmCardPayment(data.clientSecret, {
            payment_method: {
              card: cardElement
            }
          });

          if (result.error) {
            cardErrors.textContent = result.error.message;
            submitPaymentButton.disabled = false;
            submitPaymentButton.innerHTML = '<i class="fas fa-lock"></i> Payer ' + paymentAmount.toFixed(2) + ' €';
          } else {
            // 3. Paiement réussi - envoyer la confirmation
            if (result.paymentIntent.status === 'succeeded') {
              await fetch('{{ route("stripe.confirm-payment") }}', {
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

              // Redirection vers la page de succès
              window.location.href = '{{ route("payment.success") }}';
            }
          }
        } catch (error) {
          console.error('Erreur:', error);
          Swal.fire({
            icon: 'error',
            title: 'Erreur de paiement',
            text: 'Une erreur est survenue lors du traitement du paiement. Veuillez réessayer.',
            timer: 4000,
            showConfirmButton: false
          });
          
          submitPaymentButton.disabled = false;
          submitPaymentButton.innerHTML = '<i class="fas fa-lock"></i> Payer ' + paymentAmount.toFixed(2) + ' €';
        }
      });
    });
  </script>
</body>

</html>