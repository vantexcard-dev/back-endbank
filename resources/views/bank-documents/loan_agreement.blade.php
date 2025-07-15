<!DOCTYPE html>
<html lang="{{ $document->language }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Contrat de prêt') }} - {{ config('app.name') }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; line-height: 1.6; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
        .parties { display: flex; justify-content: space-between; margin-bottom: 30px; gap: 20px; }
        .party { flex: 1; padding: 15px; background: #f9f9f9; border-radius: 5px; }
        .section { margin-bottom: 25px; page-break-inside: avoid; }
        .section-title { font-weight: bold; margin-bottom: 10px; color: #2c3e50; }
        .signature-area { margin-top: 50px; display: flex; justify-content: space-between; }
        .signature { width: 45%; border-top: 1px solid #000; padding-top: 5px; text-align: center; }
        .footer { margin-top: 30px; font-size: 0.8em; text-align: center; color: #777; }
        .important { font-weight: bold; }
        .underline { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('CONTRAT DE PRÊT') }}</h1>
        <p>{{ __('N°') }}: PRT-{{ str_pad($document->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>
    
    <div class="parties">
        <div class="party">
            <div class="important">{{ __('LE PRÊTEUR') }}:</div>
            <p>{{ config('app.name') }}</p>
            <p>{{ __('Siège social') }}: 123 Avenue des Banques, 75001 Paris</p>
        </div>
        
        <div class="party">
            <div class="important">{{ __('L\'EMPRUNTEUR') }}:</div>
            <p>{{ $user->name }}</p>
            <p>{{ __('Né(e) le') }}: {{ $formatted_birth_date }}</p>
            <p>{{ __('Adresse') }}: {{ $user->address ?? 'Non spécifié' }}</p>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">{{ __('ARTICLE 1 - OBJET') }}</div>
        <p>{{ __('Le présent contrat a pour objet de définir les conditions du prêt d\'un montant de') }} 
        <span class="important">{{ number_format($document->metadata['amount'], 2, ',', ' ') }} €</span> 
        {{ __('(soit') }} <span class="underline">{{ $amount_in_words }})</span>.</p>
    </div>
    
    <div class="section">
        <div class="section-title">{{ __('ARTICLE 2 - CARACTÉRISTIQUES') }}</div>
        <table>
            <tr>
                <th>{{ __('Montant') }}</th>
                <td>{{ number_format($document->metadata['amount'], 2, ',', ' ') }} €</td>
            </tr>
            <tr>
                <th>{{ __('Durée') }}</th>
                <td>{{ $document->metadata['duration'] }} {{ __('mois') }}</td>
            </tr>
            <tr>
                <th>{{ __('Taux') }}</th>
                <td>{{ $document->metadata['rate'] }}% {{ __('fixe') }}</td>
            </tr>
            <tr>
                <th>{{ __('Date de début') }}</th>
                <td>{{ $formatted_start_date }}</td>
            </tr>
            <tr>
                <th>{{ __('Mensualité') }}</th>
                <td>{{ number_format($document->metadata['monthly_payment'], 2, ',', ' ') }} €</td>
            </tr>
        </table>
    </div>
    
    <div class="section">
        <div class="section-title">{{ __('ARTICLE 3 - MODALITÉS DE PAIEMENT') }}</div>
        <p>{{ __('Paiement par prélèvement automatique le 5 de chaque mois à compter du') }} 
        {{ \Carbon\Carbon::parse($document->metadata['start_date'])->addMonth()->format('d/m/Y') }}.</p>
    </div>
    
    <div class="signature-area">
        <div class="signature">
            <p>{{ __('Fait à Paris, le') }} {{ $date }}</p>
            <p>{{ __('Pour le Prêteur') }},</p>
            <p>{{ __('Signature') }}</p>
        </div>
        
        <div class="signature">
            <p>{{ __('Fait à') }} ____________________, {{ __('le') }} ____/____/______</p>
            <p>{{ __('Pour l\'Emprunteur') }},</p>
            <p>{{ __('Signature') }}</p>
        </div>
    </div>
    
    <div class="footer">
        <p>{{ __('Document généré le') }} {{ $date }} - {{ config