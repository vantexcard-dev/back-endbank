<!DOCTYPE html>
<html lang="{{ $document->language }}">
<head>
    <meta charset="UTF-8">
    <title>Preuve de paiement - {{ config('app.name') }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; line-height: 1.6; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 50px; font-size: 0.9em; text-align: center; }
        .signature { margin-top: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>PREUVE DE PAIEMENT</h2>
        <p>Référence: PAI-{{ str_pad($document->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>
    
    <div class="content">
        <table>
            <tr>
                <th>Client</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>Montant</th>
                <td>{{ number_format($document->metadata['payment_amount'], 2, ',', ' ') }} €</td>
            </tr>
            <tr>
                <th>Date</th>
                <td>{{ $formatted_payment_date }}</td>
            </tr>
            <tr>
                <th>Méthode de paiement</th>
                <td>{{ $payment_method_label }}</td>
            </tr>
            @if(!empty($document->metadata['transaction_reference']))
            <tr>
                <th>Référence</th>
                <td>{{ $document->metadata['transaction_reference'] }}</td>
            </tr>
            @endif
            @if(!empty($document->metadata['payment_notes']))
            <tr>
                <th>Notes</th>
                <td>{{ $document->metadata['payment_notes'] }}</td>
            </tr>
            @endif
        </table>
    </div>
    
    <div class="footer">
        <p>Document généré le {{ $date }} par {{ config('app.name') }}</p>
    </div>
</body>
</html>