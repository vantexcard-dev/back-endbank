@include('layout.app')
@include('layout.header')

<div class="container-fluid vh-100 d-flex justify-content-center align-items-center">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm p-4">
                <h2 class="mb-4 text-center">{{__('messages.saisir_code')}}</h2>

                <form action="{{ route('valider-code', ['etape' => 'confirmation']) }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="code">{{__('messages.code_confirmation')}}</label>
                        <input type="text" name="code" id="code" class="form-control" placeholder="Saisir le code" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">{{__('messages.valider_code')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('layout.footer')
