@include('layout.app')
@include('layout.header')





<main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
    <div class="title-group mb-3">
        <h1 class="h2 mb-0">{{__('messages.formulairedetransfert')}}</h1>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="row my-4">
        <div class="col-lg-7 col-12">
            <div class="custom-block bg-white">


                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="profile-tab-pane" role="tabpanel"
                        aria-labelledby="profile-tab" tabindex="0">
                        <h6 class="mb-4">Transfert d'argent</h6>

                        <form class="custom-form transfer-form" action="{{route('transfer.send')}}" method="POST" role="form">
                            @csrf

                            <input type="text" name="recipient" value="{{ old('receiver_name') }}" id="receiver_name"
                                name="receiver_name" class="form-control mb-3"
                                placeholder="{{__('messages.recipient_name')}}" required>
                            <input type="text" class="form-control mb-3" id="receiver_iban"
                                name="receiver_iban"
                                value="{{ old('receiver_iban') }}"
                                placeholder="{{__('messages.recipient_account_number')}}" required>
                            <input type="text" class="form-control mb-3" id="receiver_bic" name="receiver_bic"
                                value="{{ old('receiver_bic') }}" placeholder="{{__('messages.recipient_bic')}}">
                            <input type="number" id="amount" name="amount" value="{{ old('amount') }}"
                                class="form-control mb-3" placeholder="{{__('messages.amount_required')}}" step="0.01" min="0"
                                required>
                            <select class="form-control" id="devise" name="devise" required>
                                <option value="">{{__('messages.select_currency')}}</option>
                                <option value="USD" {{ old('target_currency') == 'USD' ? 'selected' : '' }}>USD -
                                    Dollar américain</option>
                                <option value="EUR" {{ old('target_currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro
                                </option>
                                <option value="GBP" {{ old('target_currency') == 'GBP' ? 'selected' : '' }}>GBP -
                                    Livre sterling</option>
                                <option value="CHF" {{ old('target_currency') == 'CHF' ? 'selected' : '' }}>CHF -
                                    Franc
                                    suisse</option>
                                <option value="CAD" {{ old('target_currency') == 'CAD' ? 'selected' : '' }}>CAD -
                                    Dollar canadien</option>
                                <option value="JPY" {{ old('target_currency') == 'JPY' ? 'selected' : '' }}>JPY - Yen
                                    japonais</option>
                                <option value="CNY" {{ old('target_currency') == 'CNY' ? 'selected' : '' }}>CNY - Yuan
                                    chinois</option>
                            </select>
                            @error('devise')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                            <input type="text" name="reason" class="form-control mb-3"
                                placeholder="{{__('messages.motif')}}">

                            <div class="d-flex">


                                <button type="submit" class="form-control ms-2 btn-primary">
                                    {{__('messages.send')}}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-5 col-12">
            <div class="custom-block custom-block-contact">
                <h6 class="mb-4">Still can’t find what you looking for?</h6>

                <p>
                    <strong>Call us:</strong>
                    <a href="tel: 305-240-9671" class="ms-2">
                        (60)
                        305-240-9671
                    </a>
                </p>

                <a href="#" class="btn custom-btn custom-btn-bg-white mt-3">
                    Chat with us
                </a>
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





<!-- JAVASCRIPT FILES -->
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>

</body>

</html>