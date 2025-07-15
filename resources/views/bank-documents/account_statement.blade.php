<!DOCTYPE html>
<html lang="{{ $document->language }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Relevé de compte') }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .info { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        .footer { margin-top: 50px; font-size: 0.8em; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Relevé de compte') }}</h1>
        <p>{{ __('Banque XYZ') }}</p>
    </div>
    
    <div class="info">
        <p><strong>{{ __('Client') }}:</strong> {{ $user->name }}</p>
        <p><strong>{{ __('Date') }}:</strong> {{ $date }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Description') }}</th>
                <th>{{ __('Débit') }}</th>
                <th>{{ __('Crédit') }}</th>
                <th>{{ __('Solde') }}</th>
            </tr>
        </thead>
        <tbody>
            <!-- Ici vous pouvez ajouter les transactions dynamiques -->
            <tr>
                <td>01/01/2023</td>
                <td>{{ __('Dépôt initial') }}</td>
                <td></td>
                <td>1 000,00 €</td>
                <td>1 000,00 €</td>
            </tr>
        </tbody>
    </table>
    
    <div class="footer">
        <p>{{ __('Document généré automatiquement le') }} {{ $date }}</p>
    </div>
</body>
</html>