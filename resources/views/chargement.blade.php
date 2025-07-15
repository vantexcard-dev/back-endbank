@include('layout.app')
@include('layout.header')

<style>
    h2 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .progress-container {
        background-color: #e0e0e0;
        border-radius: 25px;
        width: 100%;
        height: 30px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .progress-bar {
        background-color: #4caf50;
        height: 100%;
        width: 0;
        transition: width 0.1s ease;
    }

    .percentage {
        font-size: 18px;
        margin-bottom: 20px;
        text-align: center;
    }
</style>

<div class="container-fluid vh-100 d-flex justify-content-center align-items-center">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="text-center">
                <h2>{{__('messages.chargement_du_transfert')}}</h2>

                <div class="progress-container">
                    <div class="progress-bar" id="progress-bar"></div>
                </div>

                <div class="percentage" id="percentage">{{ $pourcentage }}%</div>
            </div>
        </div>
    </div>
</div>

<script>
    let progressBar = document.getElementById("progress-bar");
    let percentageText = document.getElementById("percentage");
    let progress = 0;
    const targetProgress = {{ $pourcentage }};
    const duration = 10000; // 10 secondes
    const intervalTime = 20;

    function animateProgress() {
        const increment = (targetProgress / (duration / intervalTime));

        const interval = setInterval(() => {
            if (progress >= targetProgress) {
                clearInterval(interval);

                // Redirections selon l'étape
                @if ($etape === 'initiation')
                    window.location.href = "{{ route('saisir-code.conf', ['etape' => 'confirmation']) }}";
                @elseif ($etape === 'confirmation')
                    window.location.href = "{{ route('saisir-code-fin', ['etape' => 'finalisation']) }}";
                @elseif ($etape === 'finalisation')
                    window.location.href = "{{ route('saisir-code-final', ['etape' => 'final']) }}";
                @elseif ($etape === 'validation')
                    window.location.href = "{{ route('page-de-succes') }}";
                @endif

            } else {
                progress += increment;
                progressBar.style.width = progress + "%";
                percentageText.innerText = Math.round(progress) + "%";
            }
        }, intervalTime);
    }

    animateProgress();
</script>

@include('layout.footer')
