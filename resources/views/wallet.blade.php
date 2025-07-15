@include('layout.app')
@include('layout.header')



<main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
                    <div class="title-group mb-3">
                        <h1 class="h2 mb-0">Wallet</h1>
                    </div>

                    <div class="row my-4">
                        <div class="col-lg-12 col-12">
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
                                                <a class="page-link" href="#">4</a>
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

                    <footer class="site-footer">
                        <div class="container">
                            <div class="row">
                                
                                <div class="col-lg-12 col-12">
                                    <p class="copyright-text">Copyright © Mini Finance 2048 
                                    - Design: <a rel="sponsored" target="_blank"></a></p>
                                </div>

                            </div>
                        </div>
                    </footer>
                </main>
