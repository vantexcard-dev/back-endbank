<!DOCTYPE html>
<html lang="{{ $document->language }}">
<head>
    <!-- Styles similaires au contrat de prêt -->
</head>
<body>
    <div class="header">
        <h1>CERTIFICAT DE NON BLANCHIMENT D'ARGENT</h1>
        <p>N°: CNB-{{ str_pad($document->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>
    
    <div class="content">
        <p>Je soussigné(e), <strong>{{ $user->name }}</strong>, certifie sur l'honneur que :</p>
        
        <ol>
            <li>Les fonds utilisés pour les opérations avec {{ config('app.name') }} proviennent de sources légitimes.</li>
            <li>Je ne suis impliqué(e) dans aucune activité de blanchiment d'argent.</li>
            <li>Toutes les informations fournies sont exactes et complètes.</li>
        </ol>
        
        <p>Fait à ____________________, le {{ $document->metadata['certification_date']->format('d/m/Y') }}</p>
        
        <div class="signature">
            <p>Signature :</p>
            <br><br>
            <p>{{ $user->name }}</p>
        </div>
    </div>
</body>
</html>