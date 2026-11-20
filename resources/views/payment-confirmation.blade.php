<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Confirmé - SteinCard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background-color: #f0f;
            animation: confetti-fall 5s linear infinite;
        }
        
        @keyframes confetti-fall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }
        
        .success-animation {
            animation: success-bounce 0.6s ease-in-out;
        }
        
        @keyframes success-bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        
        .pulse-glow {
            animation: pulse-glow 2s infinite;
        }
        
        @keyframes pulse-glow {
            0% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <!-- Confetti Animation -->
    <div id="confetti-container"></div>
    
    <div class="max-w-2xl w-full">
        <!-- Carte de confirmation -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- En-tête avec dégradé -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 py-8 px-6 text-center text-white">
                <div class="success-animation">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 pulse-glow">
                        <i class="fas fa-check text-green-500 text-3xl"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-bold mb-2">Paiement Confirmé !</h1>
                <p class="text-green-100 opacity-90">Votre transaction a été traitée avec succès</p>
            </div>
            
            <!-- Contenu principal -->
            <div class="p-8">
                <!-- Résumé du paiement -->
                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-receipt mr-3 text-green-500"></i>
                        Récapitulatif de votre paiement
                    </h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-600">Référence :</span>
                            <span class="font-mono font-semibold text-gray-800" id="payment-reference">-</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-600">Montant :</span>
                            <span class="font-semibold text-gray-800" id="payment-amount">-</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-600">Description :</span>
                            <span class="font-semibold text-gray-800 text-right" id="payment-description">-</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-600">Date :</span>
                            <span class="font-semibold text-gray-800" id="payment-date">-</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600">Statut :</span>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                <i class="fas fa-check-circle mr-1"></i>
                                Payé
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Message de confirmation -->
                <div class="text-center mb-8">
                    <div class="flex items-center justify-center mb-4 text-green-500">
                        <i class="fas fa-envelope-open-text text-4xl mr-3"></i>
                        <i class="fas fa-file-pdf text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">
                        Votre reçu a été téléchargé
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Un reçu détaillé a été généré et téléchargé automatiquement. 
                        Conservez-le pour vos archives.
                    </p>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 inline-block">
                        <p class="text-blue-800 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            Le reçu a été envoyé à votre adresse email
                        </p>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <button onclick="downloadReceiptAgain()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-download mr-2"></i>
                        Télécharger à nouveau
                    </button>
                    <button onclick="goToHomepage()" 
                            class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-home mr-2"></i>
                        Retour à l'accueil
                    </button>
                </div>
                
                <!-- Informations supplémentaires -->
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <h4 class="font-semibold text-amber-800 mb-2 flex items-center">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Paiement sécurisé
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-amber-700">
                        <div class="flex items-center">
                            <i class="fas fa-lock mr-2"></i>
                            <span>Chiffrement SSL</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Transaction vérifiée</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-file-invoice mr-2"></i>
                            <span>Reçu disponible 24/7</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pied de page -->
            <div class="bg-gray-100 py-4 px-6 text-center">
                <p class="text-gray-600 text-sm">
                    <i class="fas fa-clock mr-1"></i>
                    Vous pouvez accéder à vos reçus à tout moment depuis votre espace client
                </p>
            </div>
        </div>
        
        <!-- Support client -->
        <div class="text-center mt-6 text-white">
            <p class="text-sm opacity-80">
                Besoin d'aide ? 
                <a href="mailto:support@steincard.online" class="underline hover:no-underline">
                    Contactez notre support
                </a>
            </p>
        </div>
    </div>

    <script>
        // Générer des confettis
        function createConfetti() {
            const container = document.getElementById('confetti-container');
            const colors = ['#f0f', '#0ff', '#ff0', '#0f0', '#00f', '#f00'];
            
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.animationDelay = Math.random() * 5 + 's';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.width = Math.random() * 10 + 5 + 'px';
                confetti.style.height = Math.random() * 10 + 5 + 'px';
                container.appendChild(confetti);
            }
        }

        // Récupérer les données du paiement depuis l'URL ou le localStorage
        function loadPaymentData() {
            // Essayer de récupérer depuis l'URL
            const urlParams = new URLSearchParams(window.location.search);
            const paymentId = urlParams.get('payment_id');
            const amount = urlParams.get('amount');
            const description = urlParams.get('description');
            
            // Si pas dans l'URL, essayer le localStorage
            const storedPayment = localStorage.getItem('last_payment');
            
            if (storedPayment) {
                const payment = JSON.parse(storedPayment);
                document.getElementById('payment-reference').textContent = payment.payment_id || 'N/A';
                document.getElementById('payment-amount').textContent = payment.amount ? payment.amount + ' €' : 'N/A';
                document.getElementById('payment-description').textContent = payment.description || 'N/A';
                document.getElementById('payment-date').textContent = new Date().toLocaleDateString('fr-FR');
            } else if (paymentId) {
                // Données depuis l'URL
                document.getElementById('payment-reference').textContent = paymentId;
                document.getElementById('payment-amount').textContent = amount ? amount + ' €' : 'N/A';
                document.getElementById('payment-description').textContent = decodeURIComponent(description || 'N/A');
                document.getElementById('payment-date').textContent = new Date().toLocaleDateString('fr-FR');
            } else {
                // Données par défaut
                document.getElementById('payment-reference').textContent = 'N/A';
                document.getElementById('payment-amount').textContent = 'N/A';
                document.getElementById('payment-description').textContent = 'N/A';
                document.getElementById('payment-date').textContent = new Date().toLocaleDateString('fr-FR');
            }
        }

        // Télécharger à nouveau le reçu
        function downloadReceiptAgain() {
            const paymentId = document.getElementById('payment-reference').textContent;
            if (paymentId && paymentId !== 'N/A') {
                const downloadUrl = `/api/receipt/${paymentId}/download`;
                window.open(downloadUrl, '_blank');
                
                // Afficher un message de confirmation
                showToast('Reçu téléchargé à nouveau !', 'success');
            } else {
                showToast('Impossible de trouver les informations du paiement', 'error');
            }
        }

        // Retour à la page d'accueil
        function goToHomepage() {
            window.location.href = '/'; // Remplacez par l'URL de votre page d'accueil
        }

        // Afficher un toast de notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 p-4 rounded-lg text-white font-semibold z-50 transform transition-transform duration-300 ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 'bg-blue-500'
            }`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            createConfetti();
            loadPaymentData();
            
            // Jouer un son de succès (optionnel)
            try {
                const audio = new Audio('data:audio/wav;base64,UklGRigAAABXQVZFZm10IBAAAAABAAEARKwAAIhYAQACABAAZGF0YQQAAAAAAA==');
                audio.volume = 0.3;
                audio.play().catch(() => {});
            } catch (e) {}
            
            // Effet de vibration subtile sur mobile
            if ('vibrate' in navigator) {
                navigator.vibrate([100, 50, 100]);
            }
        });

        // Empêcher le retour en arrière vers la page de paiement
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function() {
            window.history.go(1);
        };
    </script>
</body>
</html>