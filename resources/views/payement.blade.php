<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Sécurisé</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .card-front, .card-back {
            backface-visibility: hidden;
            transition: transform 0.6s;
        }
        
        .card-back {
            transform: rotateY(180deg);
        }
        
        .card-container.flipped .card-front {
            transform: rotateY(180deg);
        }
        
        .card-container.flipped .card-back {
            transform: rotateY(0);
        }
        
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
        }
        
        .fee-notice {
            background-color: #fef3cd;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 12px;
            margin-top: 10px;
        }
        
        .payment-method-card {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-method-card:hover {
            border-color: #3b82f6;
        }
        
        .payment-method-card.selected {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        
        .ideal-bank-element {
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            margin-top: 8px;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-4xl w-full grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Informations de paiement -->
        <div class="bg-white rounded-2xl shadow-xl p-6 lg:p-8">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Secure payment</h1>
                <div class="flex space-x-2">
                    <div class="w-10 h-6 bg-blue-600 rounded-md"></div>
                    <div class="w-10 h-6 bg-yellow-400 rounded-md"></div>
                    <div class="w-10 h-6 bg-red-600 rounded-md"></div>
                    <div class="w-10 h-6 bg-green-500 rounded-md"></div>
                </div>
            </div>

            <!-- Messages d'erreur -->
            <div id="card-errors" class="text-red-500 text-sm mt-2 mb-4 p-3 bg-red-50 rounded-lg hidden" role="alert"></div>

            <form id="payment-form" class="space-y-6">
                <!-- Motif du paiement -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Reason for payment</label>
                    <input type="text" value="Service Regulations" id="description" name="description" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="Ex: Services de consultation">
                </div>
                
                <!-- Montant -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (€)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500">€</span>
                        </div>
                        <input type="number" id="amount" name="amount" min="1" step="0.01" required 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="0.00">
                    </div>
                    <div class="fee-notice mt-2">
                        <div class="flex items-center text-amber-800 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>A 3% transaction fee applies.</span>
                        </div>
                    </div>
                </div>

                <!-- Méthode de paiement -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Payment method</label>
                    <div class="space-y-3">
                        <!-- Carte bancaire -->
                        <div class="payment-method-card selected" data-method="card">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment-method" value="card" checked class="hidden">
                                <div class="flex items-center justify-between w-full">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full border-2 border-blue-500 flex items-center justify-center mr-3">
                                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                                        </div>
                                        <span class="flex items-center font-medium">
                                            <i class="fab fa-cc-visa text-blue-600 mr-2 text-lg"></i>
                                            <i class="fab fa-cc-mastercard text-red-600 mr-2 text-lg"></i>
                                            Bank card
                                        </span>
                                    </div>
                                    <div class="flex space-x-1">
                                        <div class="w-8 h-5 bg-blue-600 rounded-sm"></div>
                                        <div class="w-8 h-5 bg-red-600 rounded-sm"></div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- iDEAL -->
                        <div class="payment-method-card" data-method="ideal">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment-method" value="ideal" class="hidden">
                                <div class="flex items-center justify-between w-full">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center mr-3">
                                            <div class="w-3 h-3 rounded-full bg-transparent"></div>
                                        </div>
                                        <span class="flex items-center font-medium">
                                            <i class="fas fa-university text-purple-600 mr-2 text-lg"></i>
                                            iDEAL
                                        </span>
                                    </div>
                                    <img src="https://cdn.worldvectorlogo.com/logos/ideal-logo.svg" alt="iDEAL" class="h-6">
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Informations de carte (visible uniquement pour carte) -->
                <div id="card-elements" class="space-y-4">
                    <h2 class="text-lg font-semibold text-gray-800">Credit card information</h2>
                    
                    <!-- Numéro de carte -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de carte</label>
                        <div id="card-number" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition">
                            <!-- Stripe Card Number Element sera injecté ici -->
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Date d'expiration -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expiration date</label>
                            <div id="card-expiry" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition">
                                <!-- Stripe Card Expiry Element sera injecté ici -->
                            </div>
                        </div>
                        
                        <!-- Code CVC -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Code CVC</label>
                            <div id="card-cvc" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition">
                                <!-- Stripe Card CVC Element sera injecté ici -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Élément iDEAL (caché par défaut) -->
                <div id="ideal-element" class="hidden">
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-800">iDEAL payment</h2>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center text-blue-700">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span class="text-sm">You will be redirected to your bank to complete the payment.</span>
                            </div>
                        </div>
                        <div id="ideal-bank" class="ideal-bank-element">
                            <!-- Élément iDEAL Stripe sera injecté ici -->
                        </div>
                    </div>
                </div>
                
                <!-- Informations personnelles -->
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-gray-800">Personal information</h2>
                    
                    <!-- Nom complet -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full name</label>
                        <input type="text" id="name" name="name" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="Jean Dupont">
                    </div>
                    
                    <!-- Email (caché mais envoyé au serveur) -->
                    <input type="hidden" id="email" name="email" value="viviengazoline@gmail.com">
                    
                    <!-- Pays -->
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <select id="country" name="country" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="" disabled selected>Select your country</option>
                            <option value="AF">Afghanistan</option>
<option value="AL">Albania</option>
<option value="DZ">Algeria</option>
<option value="AD">Andorra</option>
<option value="AO">Angola</option>
<option value="AG">Antigua and Barbuda</option>
<option value="AR">Argentina</option>
<option value="AM">Armenia</option>
<option value="AU">Australia</option>
<option value="AT">Austria</option>
<option value="AZ">Azerbaijan</option>
<option value="BS">Bahamas</option>
<option value="BH">Bahrain</option>
<option value="BD">Bangladesh</option>
<option value="BB">Barbados</option>
<option value="BY">Belarus</option>
<option value="BE">Belgium</option>
<option value="BZ">Belize</option>
<option value="BJ">Benin</option>
<option value="BT">Bhutan</option>
<option value="BO">Bolivia</option>
<option value="BA">Bosnia and Herzegovina</option>
<option value="BW">Botswana</option>
<option value="BR">Brazil</option>
<option value="BN">Brunei</option>
<option value="BG">Bulgaria</option>
<option value="BF">Burkina Faso</option>
<option value="BI">Burundi</option>
<option value="KH">Cambodia</option>
<option value="CM">Cameroon</option>
<option value="CA">Canada</option>
<option value="CV">Cape Verde</option>
<option value="CF">Central African Republic</option>
<option value="TD">Chad</option>
<option value="CL">Chile</option>
<option value="CN">China</option>
<option value="CO">Colombia</option>
<option value="KM">Comoros</option>
<option value="CG">Congo</option>
<option value="CD">Congo, Democratic Republic</option>
<option value="CR">Costa Rica</option>
<option value="HR">Croatia</option>
<option value="CU">Cuba</option>
<option value="CY">Cyprus</option>
<option value="CZ">Czech Republic</option>
<option value="DK">Denmark</option>
<option value="DJ">Djibouti</option>
<option value="DM">Dominica</option>
<option value="DO">Dominican Republic</option>
<option value="EC">Ecuador</option>
<option value="EG">Egypt</option>
<option value="SV">El Salvador</option>
<option value="GQ">Equatorial Guinea</option>
<option value="ER">Eritrea</option>
<option value="EE">Estonia</option>
<option value="SZ">Eswatini</option>
<option value="ET">Ethiopia</option>
<option value="FJ">Fiji</option>
<option value="FI">Finland</option>
<option value="FR">France</option>
<option value="GA">Gabon</option>
<option value="GM">Gambia</option>
<option value="GE">Georgia</option>
<option value="DE">Germany</option>
<option value="GH">Ghana</option>
<option value="GR">Greece</option>
<option value="GD">Grenada</option>
<option value="GT">Guatemala</option>
<option value="GN">Guinea</option>
<option value="GW">Guinea-Bissau</option>
<option value="GY">Guyana</option>
<option value="HT">Haiti</option>
<option value="HN">Honduras</option>
<option value="HU">Hungary</option>
<option value="IS">Iceland</option>
<option value="IN">India</option>
<option value="ID">Indonesia</option>
<option value="IR">Iran</option>
<option value="IQ">Iraq</option>
<option value="IE">Ireland</option>
<option value="IL">Israel</option>
<option value="IT">Italy</option>
<option value="JM">Jamaica</option>
<option value="JP">Japan</option>
<option value="JO">Jordan</option>
<option value="KZ">Kazakhstan</option>
<option value="KE">Kenya</option>
<option value="KI">Kiribati</option>
<option value="KP">Korea, North</option>
<option value="KR">Korea, South</option>
<option value="KW">Kuwait</option>
<option value="KG">Kyrgyzstan</option>
<option value="LA">Laos</option>
<option value="LV">Latvia</option>
<option value="LB">Lebanon</option>
<option value="LS">Lesotho</option>
<option value="LR">Liberia</option>
<option value="LY">Libya</option>
<option value="LI">Liechtenstein</option>
<option value="LT">Lithuania</option>
<option value="LU">Luxembourg</option>
<option value="MG">Madagascar</option>
<option value="MW">Malawi</option>
<option value="MY">Malaysia</option>
<option value="MV">Maldives</option>
<option value="ML">Mali</option>
<option value="MT">Malta</option>
<option value="MH">Marshall Islands</option>
<option value="MR">Mauritania</option>
<option value="MU">Mauritius</option>
<option value="MX">Mexico</option>
<option value="FM">Micronesia</option>
<option value="MD">Moldova</option>
<option value="MC">Monaco</option>
<option value="MN">Mongolia</option>
<option value="ME">Montenegro</option>
<option value="MA">Morocco</option>
<option value="MZ">Mozambique</option>
<option value="MM">Myanmar</option>
<option value="NA">Namibia</option>
<option value="NR">Nauru</option>
<option value="NP">Nepal</option>
<option value="NL">Netherlands</option>
<option value="NZ">New Zealand</option>
<option value="NI">Nicaragua</option>
<option value="NE">Niger</option>
<option value="NG">Nigeria</option>
<option value="MK">North Macedonia</option>
<option value="NO">Norway</option>
<option value="OM">Oman</option>
<option value="PK">Pakistan</option>
<option value="PW">Palau</option>
<option value="PA">Panama</option>
<option value="PG">Papua New Guinea</option>
<option value="PY">Paraguay</option>
<option value="PE">Peru</option>
<option value="PH">Philippines</option>
<option value="PL">Poland</option>
<option value="PT">Portugal</option>
<option value="QA">Qatar</option>
<option value="RO">Romania</option>
<option value="RU">Russia</option>
<option value="RW">Rwanda</option>
<option value="KN">Saint Kitts and Nevis</option>
<option value="LC">Saint Lucia</option>
<option value="VC">Saint Vincent and the Grenadines</option>
<option value="WS">Samoa</option>
<option value="SM">San Marino</option>
<option value="ST">Sao Tome and Principe</option>
<option value="SA">Saudi Arabia</option>
<option value="SN">Senegal</option>
<option value="RS">Serbia</option>
<option value="SC">Seychelles</option>
<option value="SL">Sierra Leone</option>
<option value="SG">Singapore</option>
<option value="SK">Slovakia</option>
<option value="SI">Slovenia</option>
<option value="SB">Solomon Islands</option>
<option value="SO">Somalia</option>
<option value="ZA">South Africa</option>
<option value="SS">South Sudan</option>
<option value="ES">Spain</option>
<option value="LK">Sri Lanka</option>
<option value="SD">Sudan</option>
<option value="SR">Suriname</option>
<option value="SE">Sweden</option>
<option value="CH">Switzerland</option>
<option value="SY">Syria</option>
<option value="TW">Taiwan</option>
<option value="TJ">Tajikistan</option>
<option value="TZ">Tanzania</option>
<option value="TH">Thailand</option>
<option value="TL">Timor-Leste</option>
<option value="TG">Togo</option>
<option value="TO">Tonga</option>
<option value="TT">Trinidad and Tobago</option>
<option value="TN">Tunisia</option>
<option value="TR">Turkey</option>
<option value="TM">Turkmenistan</option>
<option value="TV">Tuvalu</option>
<option value="UG">Uganda</option>
<option value="UA">Ukraine</option>
<option value="AE">United Arab Emirates</option>
<option value="GB">United Kingdom</option>
<option value="US">United States</option>
<option value="UY">Uruguay</option>
<option value="UZ">Uzbekistan</option>
<option value="VU">Vanuatu</option>
<option value="VA">Vatican City</option>
<option value="VE">Venezuela</option>
<option value="VN">Vietnam</option>
<option value="YE">Yemen</option>
<option value="ZM">Zambia</option>
<option value="ZW">Zimbabwe</option>
<option value="other">Other</option>

                        </select>
                    </div>
                    
                    <!-- Adresse -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input type="text" id="address" name="address" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="123 Rue de la Paix">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Ville -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" id="city" name="city" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   placeholder="Paris">
                        </div>
                        
                        <!-- Code postal -->
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Code postal</label>
                            <input type="text" id="postal_code" name="postal_code" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   placeholder="75001">
                        </div>
                    </div>
                </div>
                
                <!-- Bouton de paiement -->
                <button type="submit" id="submit-button" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center shadow-lg">
                    <span id="button-text">Pay now</span>
                    <div id="spinner" class="hidden ml-2">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </button>
                
                <!-- Sécurité -->
                <div class="flex items-center justify-center text-sm text-gray-500">
                    <i class="fas fa-lock mr-2"></i>
                    <span>100% secure payment with VantexCard</span>
                </div>
            </form>
        </div>
        
        <!-- Aperçu de la carte et récapitulatif -->
        <div class="flex flex-col space-y-8">
            <!-- Aperçu de la carte -->
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-6 text-white">
                <div class="card-container relative w-full h-48 mx-auto">
                    <!-- Face avant de la carte -->
                    <div class="card-front absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-5 shadow-lg">
                        <div class="flex justify-between items-start mb-8">
                            <div class="text-xl font-semibold">Bank Card</div>
                            <div class="w-12 h-8 bg-yellow-400 rounded"></div>
                        </div>
                        <div class="mb-4">
                            <div id="card-preview-number" class="text-xl tracking-widest font-mono">•••• •••• •••• ••••</div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-xs opacity-80">Cardholder</div>
                                <div id="card-preview-name" class="font-medium">NAME SURNAME</div>
                            </div>
                            <div>
                                <div class="text-xs opacity-80">Expire</div>
                                <div id="card-preview-expiry" class="font-medium">MM/AA</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Face arrière de la carte -->
                    <div class="card-back absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-5 shadow-lg">
                        <div class="h-10 bg-black mt-4 mb-6"></div>
                        <div class="flex justify-end px-4">
                            <div class="w-16 h-8 bg-gray-200 rounded flex items-center justify-center">
                                <span id="card-preview-cvc" class="text-gray-800 font-mono">•••</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-center mt-6">
                    <button id="flip-card" class="text-sm bg-white bg-opacity-20 hover:bg-opacity-30 py-1 px-3 rounded-full transition">
                        <i class="fas fa-sync-alt mr-1"></i> Turn the card over
                    </button>
                </div>
            </div>
            
            <!-- Récapitulatif -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Summary</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Reason for payment:</span>
                        <span id="summary-description" class="font-medium text-right">-</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Amount:</span>
                        <span id="summary-amount" class="font-medium">-</span>
                    </div>
                    
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Transaction fees (3%):</span>
                        <span id="summary-fees" class="font-medium">-</span>
                    </div>
                    
                    <div class="border-t pt-3 flex justify-between text-lg font-bold">
                        <span>Total:</span>
                        <span id="summary-total">-</span>
                    </div>
                </div>
                
                <!-- Détail des frais -->
                <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center text-blue-700 text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span>The total amount includes a 3% transaction fee.</span>
                    </div>
                </div>
                
                <!-- Garanties -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="font-semibold text-gray-700 mb-3">Guarantees</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-green-500 mt-1 mr-3"></i>
                            <span class="text-sm text-gray-600">100% secure payment with SSL encryption</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-lock text-green-500 mt-1 mr-3"></i>
                            <span class="text-sm text-gray-600">Your data is protected and is never stored</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-undo-alt text-green-500 mt-1 mr-3"></i>
                            <span class="text-sm text-gray-600">30-day money-back guarantee if not satisfied</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // -----------------------------
    // Initialisation Stripe
    // -----------------------------
    const stripe = Stripe('pk_live_51SIVUUJ5IKbc9E3Nzn7lVAkknCankSCBwQ2Wm8TNsEUaOWZqLPPCADBR7E6w9hfXukmg5B4vwyWiEvG7tQ8WC9iB00EBuj1QGX');
    const elements = stripe.elements();
    const TRANSACTION_FEE_RATE = 0.03;

    let idealElement = null;
    let currentPaymentMethod = 'card';
    let currentPaymentIntentId = null;

    // -----------------------------
    // Style Stripe Elements
    // -----------------------------
    const style = {
        base: {
            color: '#32325d',
            fontFamily: '"Inter", sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': { color: '#a0aec0' }
        },
        invalid: { color: '#e53e3e', iconColor: '#e53e3e' }
    };

    // -----------------------------
    // Création des Elements Carte
    // -----------------------------
    const cardNumber = elements.create('cardNumber', { style });
    const cardExpiry = elements.create('cardExpiry', { style });
    const cardCvc = elements.create('cardCvc', { style });
    
    cardNumber.mount('#card-number');
    cardExpiry.mount('#card-expiry');
    cardCvc.mount('#card-cvc');

    // -----------------------------
    // Gestion des méthodes de paiement
    // -----------------------------
    const paymentMethodCards = document.querySelectorAll('.payment-method-card');
    
    paymentMethodCards.forEach(card => {
        card.addEventListener('click', function() {
            // Désélectionner toutes les cartes
            paymentMethodCards.forEach(c => {
                c.classList.remove('selected');
                c.querySelector('.w-6').classList.remove('border-blue-500');
                c.querySelector('.w-6').classList.add('border-gray-300');
                c.querySelector('.w-3').classList.remove('bg-blue-500');
                c.querySelector('.w-3').classList.add('bg-transparent');
            });
            
            // Sélectionner la carte cliquée
            this.classList.add('selected');
            this.querySelector('.w-6').classList.add('border-blue-500');
            this.querySelector('.w-6').classList.remove('border-gray-300');
            this.querySelector('.w-3').classList.add('bg-blue-500');
            this.querySelector('.w-3').classList.remove('bg-transparent');
            
            // Cocher le radio correspondant
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Mettre à jour l'interface
            currentPaymentMethod = radio.value;
            updatePaymentMethodUI();
        });
    });

    function updatePaymentMethodUI() {
        const cardElements = document.getElementById('card-elements');
        const idealElementDiv = document.getElementById('ideal-element');
        
        if (currentPaymentMethod === 'card') {
            cardElements.style.display = 'block';
            idealElementDiv.style.display = 'none';
        } else {
            cardElements.style.display = 'none';
            idealElementDiv.style.display = 'block';
            
            // Initialiser l'élément iDEAL si nécessaire
            if (!idealElement) {
                idealElement = elements.create('idealBank', {
                    style: style,
                    hideIcon: false
                });
                idealElement.mount('#ideal-bank');
            }
        }
    }

    // -----------------------------
    // Flip Carte
    // -----------------------------
    const flipBtn = document.getElementById('flip-card');
    if (flipBtn) {
        flipBtn.addEventListener('click', () => {
            const container = document.querySelector('.card-container');
            if (container) container.classList.toggle('flipped');
        });
    }

    // -----------------------------
    // Aperçu Carte
    // -----------------------------
    function updateCardPreview(id, value, fallback) {
        const el = document.getElementById(id);
        if (el) el.textContent = value || fallback;
    }

    cardNumber.on('change', e => updateCardPreview('card-preview-number', e.value?.formatted, '•••• •••• •••• ••••'));
    cardExpiry.on('change', e => updateCardPreview('card-preview-expiry', e.value?.formatted, 'MM/AA'));
    cardCvc.on('change', e => updateCardPreview('card-preview-cvc', e.value?.formatted, '•••'));

    // -----------------------------
    // Récapitulatif
    // -----------------------------
    const amountInputEl = document.getElementById('amount');
    const descriptionEl = document.getElementById('description');
    const nameEl = document.getElementById('name');

    if (amountInputEl) amountInputEl.addEventListener('input', updateSummary);
    if (descriptionEl) descriptionEl.addEventListener('input', updateSummary);
    if (nameEl) nameEl.addEventListener('input', function() {
        updateCardPreview('card-preview-name', this.value.toUpperCase(), 'NOM PRÉNOM');
    });

    function calculateFees(amount) {
        return Math.round(amount * TRANSACTION_FEE_RATE * 100) / 100;
    }

    function updateSummary() {
        const amountInput = amountInputEl?.value;
        const description = descriptionEl?.value;
        const sumDesc = document.getElementById('summary-description');
        if (sumDesc) sumDesc.textContent = description || '-';

        if (amountInput) {
            const amount = parseFloat(amountInput);
            const fees = calculateFees(amount);
            const total = amount + fees;
            
            ['summary-amount','summary-fees','summary-total'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    if (id === 'summary-amount') el.textContent = amount.toFixed(2) + ' €';
                    else if (id === 'summary-fees') el.textContent = fees.toFixed(2) + ' €';
                    else el.textContent = total.toFixed(2) + ' €';
                }
            });
        } else {
            ['summary-amount','summary-fees','summary-total'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = '-';
            });
        }
    }
    updateSummary();

    // -----------------------------
    // Gestion formulaire - COMPLÈTEMENT RECOMPOSÉ
    // -----------------------------
    const form = document.getElementById('payment-form');
    if (form) {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            
            const spinner = document.getElementById('spinner');
            const buttonText = document.getElementById('button-text');
            const submitBtn = document.getElementById('submit-button');
            const errorDiv = document.getElementById('card-errors');
            
            // Réinitialiser les erreurs
            if (errorDiv) {
                errorDiv.textContent = '';
                errorDiv.classList.add('hidden');
            }
            
            // Désactiver le bouton et afficher le spinner
            spinner.classList.remove('hidden');
            buttonText.textContent = 'Traitement en cours...';
            submitBtn.disabled = true;

            try {
                const formData = new FormData(form);
                const amount = parseFloat(formData.get('amount'));
                
                // Validation du montant
                if (!amount || amount <= 0) {
                    throw new Error('Veuillez saisir un montant valide');
                }

                if (amount < 0.50) {
                    throw new Error('Le montant minimum est de 0.50€');
                }
                
                const fees = calculateFees(amount);
                const totalAmount = amount + fees;

                const paymentMethodType = document.querySelector('input[name="payment-method"]:checked').value;

                console.log('🔄 Début du processus de paiement', {
                    amount: totalAmount,
                    payment_method_type: paymentMethodType
                });

                // ÉTAPE 1: Créer le PaymentIntent
                let paymentResult;
                
                // Si nous n'avons pas de PaymentIntent actuel ou si la méthode a changé, en créer un nouveau
                if (!currentPaymentIntentId || isPaymentMethodChanged()) {
                    console.log('📝 Création d\'un nouveau PaymentIntent');
                    paymentResult = await createPaymentIntent({
                        amount: totalAmount,
                        original_amount: amount,
                        fees: fees,
                        description: formData.get('description'),
                        email: formData.get('email'),
                        payment_method_type: paymentMethodType,
                        billing_details: {
                            name: formData.get('name'),
                            email: formData.get('email'),
                            address: {
                                line1: formData.get('address'),
                                city: formData.get('city'),
                                postal_code: formData.get('postal_code'),
                                country: formData.get('country')
                            }
                        }
                    });

                    if (!paymentResult.success) {
                        throw new Error(paymentResult.error);
                    }

                    // Sauvegarder l'ID du PaymentIntent pour réutilisation
                    currentPaymentIntentId = paymentResult.payment_intent_id;
                    console.log('✅ PaymentIntent créé:', currentPaymentIntentId);
                } else {
                    // Réutiliser le PaymentIntent existant
                    console.log('♻️ Réutilisation du PaymentIntent existant:', currentPaymentIntentId);
                    paymentResult = {
                        success: true,
                        payment_intent_id: currentPaymentIntentId,
                        client_secret: await getClientSecret(currentPaymentIntentId)
                    };
                }

                // ÉTAPE 2: Traitement selon la méthode de paiement
                if (paymentMethodType === 'card') {
                    await processCardPayment(paymentResult, formData, totalAmount);
                } else if (paymentMethodType === 'ideal') {
                    await processIdealPayment(paymentResult, formData);
                }

            } catch (error) {
                console.error('❌ Erreur de paiement:', error);
                if (errorDiv) {
                    errorDiv.textContent = error.message;
                    errorDiv.classList.remove('hidden');
                    
                    // Faire défiler jusqu'à l'erreur
                    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }

                // Réinitialiser le PaymentIntent en cas d'erreur
                currentPaymentIntentId = null;
            } finally {
                spinner.classList.add('hidden');
                buttonText.textContent = 'Payer maintenant';
                submitBtn.disabled = false;
            }
        });
    }

    // -----------------------------
    // Fonctions auxiliaires
    // -----------------------------

    // Vérifier si la méthode de paiement a changé
    function isPaymentMethodChanged() {
        // Pour l'instant, toujours considérer comme changé pour simplifier
        return true;
    }

    // Créer un nouveau PaymentIntent
async function createPaymentIntent(paymentData) {
    try {
        console.log('📤 Envoi demande PaymentIntent:', { 
            ...paymentData,
            existing_payment_intent_id: window.currentPaymentIntentId || null
        });
        
        const requestBody = { 
            ...paymentData,
            existing_payment_intent_id: window.currentPaymentIntentId || null
        };
        
        const response = await fetch('/api/create-payment-intent', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(requestBody)
        });

        const result = await response.json();
        console.log('📥 Réponse PaymentIntent:', result);
        
        // Sauvegarder l'ID du PaymentIntent pour réutilisation future
        if (result.success && result.payment_intent_id) {
            window.currentPaymentIntentId = result.payment_intent_id;
            console.log('💾 PaymentIntent sauvegardé:', window.currentPaymentIntentId);
            
            if (result.reused) {
                console.log('♻️ PaymentIntent réutilisé avec succès');
            } else {
                console.log('🆕 Nouveau PaymentIntent créé');
            }
        }
        
        return result;
        
    } catch (error) {
        console.error('❌ Erreur création PaymentIntent:', error);
        // Réinitialiser le PaymentIntent en cas d'erreur
        window.currentPaymentIntentId = null;
        return {
            success: false,
            error: 'Erreur de connexion. Veuillez réessayer.'
        };
    }
}

// Ajoutez cette fonction pour réinitialiser manuellement
function resetPaymentIntent() {
    window.currentPaymentIntentId = null;
    console.log('🔄 PaymentIntent réinitialisé');
    return true;
}

// Réinitialiser le PaymentIntent quand les données importantes changent
['amount', 'description', 'payment-method'].forEach(identifier => {
    const elements = document.querySelectorAll(`[name="${identifier}"], #${identifier}`);
    elements.forEach(element => {
        element.addEventListener('change', () => {
            resetPaymentIntent();
            console.log(`🔄 PaymentIntent réinitialisé due au changement: ${identifier}`);
        });
    });
});

// Réinitialiser quand on change de méthode de paiement
document.querySelectorAll('input[name="payment-method"]').forEach(radio => {
    radio.addEventListener('change', () => {
        resetPaymentIntent();
        console.log('🔄 PaymentIntent réinitialisé due au changement de méthode de paiement');
    });
});

    // Récupérer le client_secret pour un PaymentIntent existant
    async function getClientSecret(paymentIntentId) {
        // Dans une implémentation réelle, vous devriez appeler votre backend
        // Pour l'instant, on recrée un nouveau PaymentIntent
        return null;
    }

    // Traitement paiement carte
    async function processCardPayment(paymentResult, formData, totalAmount) {
        console.log('💳 Traitement paiement carte...');

        try {
            // Paiement par carte
            const { error, paymentIntent } = await stripe.confirmCardPayment(paymentResult.client_secret, {
                payment_method: {
                    card: cardNumber,
                    billing_details: {
                        name: formData.get('name'),
                        email: formData.get('email'),
                        address: {
                            line1: formData.get('address'),
                            city: formData.get('city'),
                            postal_code: formData.get('postal_code'),
                            country: formData.get('country')
                        }
                    }
                }
            });

            if (error) {
                console.error('❌ Erreur Stripe:', error);
                
                // Gestion spécifique des erreurs courantes
                if (error.code === 'resource_missing') {
                    throw new Error('Session expirée. Veuillez actualiser la page et réessayer.');
                } else if (error.code === 'payment_intent_unexpected_state') {
                    throw new Error('État de paiement invalide. Veuillez réessayer.');
                } else if (error.type === 'card_error') {
                    throw new Error(`Erreur carte: ${error.message}`);
                } else {
                    throw new Error(`Erreur de paiement: ${error.message}`);
                }
            }

            console.log('✅ Réponse Stripe:', paymentIntent);

            // Gestion des différents statuts
            switch (paymentIntent.status) {
                case 'succeeded':
                    console.log('🎉 Paiement réussi!');
                    await finalizePayment(paymentResult.payment_intent_id, totalAmount, formData);
                    break;

                case 'requires_action':
                case 'requires_confirmation':
                    console.log('🔄 Paiement nécessite action supplémentaire');
                    // Stripe gère automatiquement la redirection 3D Secure
                    break;

                case 'processing':
                    console.log('⏳ Paiement en cours de traitement');
                    await finalizePayment(paymentResult.payment_intent_id, totalAmount, formData, 'processing');
                    break;

                default:
                    console.warn('⚠️ Statut inattendu:', paymentIntent.status);
                    throw new Error(`Statut de paiement non géré: ${paymentIntent.status}`);
            }

        } catch (error) {
            console.error('❌ Erreur traitement carte:', error);
            throw error;
        }
    }

    // Traitement paiement iDEAL
    async function processIdealPayment(paymentResult, formData) {
        console.log('🏦 Traitement paiement iDEAL...');

        try {
            // S'assurer que l'élément iDEAL est prêt
            if (!idealElement) {
                idealElement = elements.create('idealBank', {
                    style: style,
                    hideIcon: false
                });
                idealElement.mount('#ideal-bank');
            }
            
            // Paiement iDEAL - redirection
            const { error } = await stripe.confirmIdealPayment(paymentResult.client_secret, {
                payment_method: {
                    ideal: idealElement,
                },
                return_url: `${window.location.origin}/payment-confirmation?payment_intent_id=${paymentResult.payment_intent_id}`,
            });

            if (error) {
                console.error('❌ Erreur iDEAL:', error);
                
                if (error.code === 'resource_missing') {
                    throw new Error('Session expirée. Veuillez actualiser la page et réessayer.');
                }
                
                throw new Error(`Erreur iDEAL: ${error.message}`);
            }
            
            // Stripe redirige automatiquement pour iDEAL
            console.log('🔄 Redirection iDEAL en cours...');
            
        } catch (error) {
            console.error('❌ Erreur traitement iDEAL:', error);
            throw error;
        }
    }

    // Finaliser le paiement
    async function finalizePayment(paymentIntentId, totalAmount, formData, status = 'succeeded') {
        try {
            console.log('📤 Finalisation du paiement:', { paymentIntentId, status });

            // Confirmer côté serveur
            const confirmResult = await confirmPayment(paymentIntentId);

            if (confirmResult.success) {
                console.log('✅ Paiement finalisé avec succès');
                
                // Redirection vers la page de confirmation
                const redirectUrl = `/payment-confirmation?payment_id=${paymentIntentId}&amount=${totalAmount}&description=${encodeURIComponent(formData.get('description'))}&status=${status}`;
                window.location.href = redirectUrl;
            } else {
                throw new Error(confirmResult.error || 'Erreur lors de la finalisation du paiement');
            }

        } catch (error) {
            console.error('❌ Erreur finalisation:', error);
            throw error;
        }
    }

    // -----------------------------
    // Fonction de confirmation de paiement
    // -----------------------------
    async function confirmPayment(paymentIntentId) {
        try {
            console.log('📞 Confirmation côté serveur:', paymentIntentId);
            
            const response = await fetch('/api/confirm-payment', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    payment_id: paymentIntentId,
                    payment_intent_id: paymentIntentId
                })
            });
            
            const result = await response.json();
            console.log('✅ Réponse confirmation:', result);
            return result;
            
        } catch (error) {
            console.error('❌ Erreur confirmation:', error);
            return {
                success: false,
                error: error.message
            };
        }
    }

    // -----------------------------
    // Gestion des retours 3D Secure et iDEAL
    // -----------------------------
    function handlePaymentReturn() {
        const urlParams = new URLSearchParams(window.location.search);
        const paymentIntentId = urlParams.get('payment_intent');
        const paymentIntentClientSecret = urlParams.get('payment_intent_client_secret');
        
        if (paymentIntentId && paymentIntentClientSecret) {
            console.log('🔄 Retour de paiement détecté:', paymentIntentId);
            processPaymentReturn(paymentIntentId, paymentIntentClientSecret);
        }
    }

    async function processPaymentReturn(paymentIntentId, clientSecret) {
        try {
            console.log('🔍 Vérification statut paiement...');
            
            // Récupérer le PaymentIntent
            const { paymentIntent, error } = await stripe.retrievePaymentIntent(clientSecret);
            
            if (error) {
                console.error('❌ Erreur récupération PaymentIntent:', error);
                showError('Erreur lors de la vérification du paiement.');
                return;
            }
            
            console.log('📊 Statut PaymentIntent:', paymentIntent.status);
            
            if (paymentIntent.status === 'succeeded') {
                // Rediriger vers la page de confirmation
                window.location.href = `/payment-confirmation?payment_id=${paymentIntentId}&status=succeeded`;
            } else if (paymentIntent.status === 'processing') {
                window.location.href = `/payment-confirmation?payment_id=${paymentIntentId}&status=processing`;
            } else {
                showError(`Paiement échoué. Statut: ${paymentIntent.status}`);
            }
            
        } catch (error) {
            console.error('❌ Erreur traitement retour:', error);
            showError('Erreur lors du traitement du retour de paiement.');
        }
    }

    function showError(message) {
        const errorDiv = document.getElementById('card-errors');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.classList.remove('hidden');
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Vérifier les retours de paiement au chargement
    handlePaymentReturn();

    // -----------------------------
    // Gestion erreurs Stripe Elements
    // -----------------------------
    [cardNumber, cardExpiry, cardCvc].forEach(el => {
        el.on('change', e => {
            const cardErrors = document.getElementById('card-errors');
            if (cardErrors) {
                if (e.error) {
                    cardErrors.textContent = e.error.message;
                    cardErrors.classList.remove('hidden');
                } else {
                    cardErrors.textContent = '';
                    cardErrors.classList.add('hidden');
                }
            }
        });
    });

    // Initialiser l'UI des méthodes de paiement
    updatePaymentMethodUI();

    // Réinitialiser le PaymentIntent si l'utilisateur change des données importantes
    ['amount', 'description', 'name'].forEach(field => {
        const element = document.getElementById(field);
        if (element) {
            element.addEventListener('input', () => {
                currentPaymentIntentId = null;
            });
        }
    });
});
</script>
</body>
</html>