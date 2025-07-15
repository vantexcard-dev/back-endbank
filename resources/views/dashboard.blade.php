@include('layout.app')
@include('layout.header')

<main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
    <div class="title-group mb-3">
        <h1 class="h2 mb-0">{{__('messages.dashboard')}}</h1>
        <small class="text-muted">Hello {{ $user->first_name }} {{ $user->last_name }}, welcome back!</small>
    </div>
    <div class="row g-4">
        @forelse($comptesBancaires as $compte)
            <div class="col-md-5 mb-3">
                <div class="custom-block custom-block-balance p-4 shadow-sm border radius"
                    style="font-size: 0.85rem; min-height: 250px;">
                    <small class="text-muted">{{__('messages.balance')}}</small>
                    <h3 class="mt-2 mb-3">{{ number_format($compte->balance, 2) }} {{ $compte->currency }}</h3>

                    <div class="mb-2">
                        <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>
                    </div>
                    <div class="mb-2">
                        <strong>{{__('messages.iban')}} :</strong>
                        <div class="text-muted">{{ $compte->iban }}</div>
                    </div>

                    <div>
                        <strong>{{__('messages.bic')}} :</strong>
                        <div class="text-muted">{{ $compte->bic }}</div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Aucun compte bancaire trouvé.</p>
        @endforelse


        <div class="col-md-7">
            <div class="card-wrapper">
                @forelse($cartesCredit as $carte)
                    <div class="credit-card">
                        <!-- Face avant -->
                        <div class="card-front">
                            <div class="card-chip"></div>

                            <div class="card-number fs-5 mb-3">
                                <span>{{ $carte->card_number }}</span>
                            </div>

                            <div class="card-footer d-flex justify-content-between mt-4">
                                <div>
                                    <small class="text-white-50">Valid Thru</small>
                                    <p class="m-0 text-white">{{ $carte->expiry_date }}</p>
                                </div>
                                <div>
                                    <small class="text-white-50">Nom</small>
                                    <div class="text-white">{{ $carte->cardholder_name ?? 'Nom inconnu' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Face arrière -->
                        <div class="card-back">
                            <div class="text-center">
                                <p style="color: white;">Code CVC</p>
                                <h3 style="color: white;">123</h3>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>Aucune carte de crédit trouvée.</p>
                @endforelse
            </div>
        </div>

    </div>


    <style>
        /* Conteneur pour gérer le flip */
        .card-wrapper {
            perspective: 1000px;
            width: 450px;
            height: 260px;
            margin: auto;
        }

        /* Carte contenant la face avant et arrière */
        .credit-card {
            width: 100%;
            height: 100%;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.8s;
        }

        /* Applique l'effet flip au survol */
        .card-wrapper:hover .credit-card {
            transform: rotateY(180deg);
        }

        /* Faces avant et arrière */
        .card-front,
        .card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 20px;
            padding: 30px;
            backface-visibility: hidden;
            background-size: cover;
            background-position: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
        }

        /* Face avant */
        .card-front {
            background-image: url('/assets/images/carte_credit-rem.png');
        }

        /* Face arrière */
        .card-back {
            background-image: url('/assets/images/carte_credit.webp');
            transform: rotateY(180deg);
            justify-content: center;
            align-items: center;
        }

        /* Éléments visuels */
        .card-chip {
            width: 60px;
            height: 40px;
            background: linear-gradient(#ccc, #999);
            border-radius: 10px;
        }

        .card-number {
            font-size: 28px;
            letter-spacing: 4px;
            display: flex;
            gap: 15px;
            margin-top: 40px;
        }

        .card-footer {
            font-size: 16px;
        }

        /* Bloc personnalisé */
        .custom-block.custom-block-balance {
            width: 450px;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .custom-block-numbers span,
        .custom-block-numbers p {
            font-size: 22px;
            margin-right: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .custom-block.custom-block-balance {
                width: 100%;
                padding: 20px;
            }

            .custom-block-numbers span,
            .custom-block-numbers p {
                font-size: 18px;
            }
        }
    </style>


    <div class="custom-block custom-block-bottom d-flex flex-wrap">
        <div class="custom-block-bottom-item">
            <a href="#" class="d-flex flex-column" data-bs-toggle="modal" data-bs-target="#topUpModal">
                <i class="custom-block-icon bi-wallet"></i>
                <small>{{__('messages.recharger')}}</small>
            </a>
        </div>
        <!-- Modal Top Up -->
        <div class="modal fade" id="topUpModal" tabindex="-1" aria-labelledby="topUpModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="topUpModalLabel">Recharger mon compte</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <label for="amount">{{__('messages.amount')}} (€)</label>
                        <input type="number" id="amount" class="form-control" placeholder="Ex: 50.00" min="1"
                            step="0.01">

                        <div id="paypal-button-container" class="mt-4"></div>

                        <div id="message" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>


        <div class="custom-block-bottom-item">
            <a href="{{route('transactions')}}" class="d-flex flex-column">
                <i class="custom-block-icon bi-send"></i>
                <small>{{__('messages.send_money')}}</small>
            </a>
        </div>
        <div class="custom-block-bottom-item">
            <a href="#" class="d-flex flex-column" data-bs-toggle="modal" data-bs-target="#paymentLinkModal">
                <i class="custom-block-icon bi-arrow-down"></i>
                <small>{{__('messages.requested')}}</small>
            </a>
        </div>
<!-- Modal de création de lien de paiement -->
<div class="modal fade" id="paymentLinkModal" tabindex="-1" aria-labelledby="paymentLinkModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('payment.links.create.amount') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="paymentLinkModalLabel">{{__('messages.Créerunlien')}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3">
            <label for="amount" class="form-label">{{__('messages.amount')}} (€)</label>
            <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0.01" placeholder="Ex : 50.00">
          </div>

          <p class="text-muted">{{__('messages.Laissezvide') }}</p>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">{{__('messages.Générerlelien')}}</button>
        </div>
      </form>
    </div>
  </div>
</div>



    </div>
    @if (isset($link) && $link)
        <div class="mb-3">
            <label for="paymentLink" class="form-label">{{__('messages.payment_link')}} :</label>
            <div class="input-group">
                <input type="text" class="form-control" id="paymentLink"
                    value="{{ route('payment.links.show', $link->token) }}" readonly>
                <button class="btn btn-outline-secondary" type="button" onclick="copyLink()">{{__('messages.copierlien')}}</button>
            </div>
            <div id="copyMessage" class="text-success mt-2" style="display: none;">{{__('messages.copy_success')}}
            </div>
        </div>
    @else
        <div class="alert alert-warning">{{__('messages.aucunlien')}}</div>
    @endif
    {{-- Liste des prêts --}}
    <div class="card mb-4">
        <div class="card-header">{{__('messages.mesprets')}}</div>
        <div class="card-body">
            @forelse($prets as $pret)
                <p><strong>{{__('messages.loan_amount')}}:</strong> {{ $pret->amount }} €</p>
                <p><strong>{{__('messages.taux')}}:</strong> {{ $pret->interest_rate }}%</p>
                <p><strong>{{__('messages.total_interest')}}:</strong> {{ $pret->total_interest }} €</p>
                <p><strong>{{__('messages.loan_duration')}}:</strong> {{ $pret->repayment_years }} ans</p>
                <p><strong>{{__('messages.payment_date')}}:</strong> {{ \Carbon\Carbon::parse($pret->payment_date)->format('d/m/Y') }}</p>
                <p><strong>{{__('messages.loan_status')}}:</strong> {{ ucfirst($pret->status) }}</p>
                <hr>
            @empty
                <p>{{__('messages.aucunpret')}}</p>
            @endforelse
        </div>
    </div>


    <div class="col-lg-12 col-12 mt-4">
        <div class="custom-block bg-white">
            <h5 class="mb-4">{{__('messages.transaction_history')}}</h5>

            <div class="table-responsive">
                @php
                    $userIbans = $comptesBancaires->pluck('iban')->toArray();
                @endphp

                <table class="account-table table">
                    <thead>
                        <tr>
                            <th scope="col">{{__('messages.date')}}</th>
                            <th scope="col">{{__('messages.heure')}}</th>
                            <th scope="col">{{__('messages.destinataire')}}</th>
                            <th scope="col">{{__('messages.transaction_type')}}</th>
                            <th scope="col">{{__('messages.montant')}}</th>
                            <th scope="col">{{__('messages.status')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            @php
                                $isOutgoing = in_array($transaction->sender_iban, $userIbans);
                                $amountClass = $isOutgoing ? 'text-danger' : 'text-success';
                                $sign = $isOutgoing ? '-' : '+';
                            @endphp

                            <tr>
                                <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                                <td>{{ $transaction->created_at->format('H:i') }}</td>
                                <td>
                                    {{ $isOutgoing ? '' . $transaction->receiver_name : 'De ' . $transaction->sender_name }}
                                </td>
                                <td>{{ ucfirst($transaction->type) }}</td>
                                <td class="{{ $amountClass }}">
                                    <span class="me-1">{{ $sign }}</span>
                                    ${{ number_format($transaction->amount, 2) }}
                                </td>
                                <td>
                                    <span
                                        class="badge text-bg-{{ strtolower($transaction->status) === 'success' ? 'success' : (strtolower($transaction->status) === 'pending' ? 'danger' : 'secondary') }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">{{__('messages.transaction_history_empty')}}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">Prev</span>
                        </a>
                    </li>
                    <li class="page-item active" aria-current="page">
                        <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
    </div>
</main>
<script>
    let rechargeAmount = 0;

    paypal.Buttons({
        style: {
            layout: 'vertical',
            color: 'blue',
            shape: 'rect',
            label: 'paypal'
        },

        onClick: function (data, actions) {
            rechargeAmount = parseFloat(document.getElementById("amount").value);
            if (isNaN(rechargeAmount) || rechargeAmount <= 0) {
                document.getElementById("message").innerHTML = "<span style='color:red;'>Veuillez entrer un montant valide.</span>";
                return actions.reject();
            }
            return actions.resolve();
        },

        // Crée la commande PayPal
        createOrder: function (data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: rechargeAmount.toFixed(2),
                        currency_code: 'EUR'
                    },
                    description: "Recharge de compte utilisateur"
                }]
            });
        },

        // Quand le paiement est approuvé
        onApprove: function (data, actions) {
            return actions.order.capture().then(function (details) {
                document.getElementById("message").innerHTML =
                    `<span style="color:green;">Recharge réussie. Merci ${details.payer.name.given_name} !</span>`;

                // Envoi des données à Laravel
                fetch('/recharge/confirm', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(details)
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        if (data.success) {
                            alert("Votre compte a été rechargé !");
                            // recharge page or update balance
                            // location.reload();
                        } else {
                            alert("Erreur serveur : " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la requête Laravel:', error);
                    });
            });
        },

        onError: function (err) {
            console.error('Erreur PayPal :', err);
            document.getElementById("message").innerHTML = "<span style='color:red;'>Erreur lors du paiement.</span>";
        }
    }).render('#paypal-button-container');
</script>

<script>
    function copyLink() {
        const input = document.getElementById("paymentLink");
        input.select();
        input.setSelectionRange(0, 99999); // pour mobile
        document.execCommand("copy");

        document.getElementById("copyMessage").style.display = "block";

        // Optionnel : masquer le message après 2s
        setTimeout(() => {
            document.getElementById("copyMessage").style.display = "none";
        }, 2000);
    }
</script>

<!-- JAVASCRIPT FILES -->
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>

</body>

</html>