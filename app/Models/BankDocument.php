<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BankDocument extends Model
{
    protected $fillable = [
        'user_id',
        'document_type',
        'language',
        'file_path',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'sent_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generateDocument()
    {
        try {
            // Validation des données selon le type de document
            $this->validateDocumentData();

            // Préparation des données de base
            $data = [
                'user' => $this->user ?? throw new \Exception("Aucun utilisateur associé"),
                'document' => $this,
                'date' => now()->format('d/m/Y'),
            ];

            // Ajout des données spécifiques
            $data = array_merge($data, $this->getDocumentSpecificData());

            // Configuration de la langue
            app()->setLocale($this->language);

            // Génération du PDF
            $pdf = Pdf::loadView("bank-documents.{$this->document_type}", $data);

            // Sauvegarde du fichier
            $filename = "{$this->document_type}_{$this->id}_".now()->format('YmdHis').'.pdf';
            $path = "documents/{$filename}";

            Storage::disk('public')->makeDirectory('documents');
            Storage::disk('public')->put($path, $pdf->output());

            $this->update(['file_path' => $path]);

            return $path;

        } catch (\Exception $e) {
            Log::error("Erreur génération document #{$this->id}: ".$e->getMessage());
            throw new \Exception("Échec de la génération du document: ".$e->getMessage());
        }
    }

    protected function validateDocumentData()
    {
        switch ($this->document_type) {
            case 'loan_agreement':
                $this->validateLoanAgreement();
                break;
            case 'anti_money_laundering':
                $this->validateAntiMoneyLaundering();
                break;
            case 'payment_proof':
                $this->validatePaymentProof();
                break;
        }
    }

    private function validateLoanAgreement()
    {
        $required = ['amount', 'duration', 'rate', 'monthly_payment', 'start_date'];
        foreach ($required as $field) {
            if (empty($this->metadata[$field])) {
                throw new \Exception("Le champ $field est requis pour le contrat de prêt");
            }
        }
    }

    private function validateAntiMoneyLaundering()
    {
        $required = ['certification_date', 'certification_number'];
        foreach ($required as $field) {
            if (empty($this->metadata[$field])) {
                throw new \Exception("Le champ $field est requis pour le certificat");
            }
        }
    }

    private function validatePaymentProof()
    {
        $required = ['payment_amount', 'payment_date', 'payment_method'];
        foreach ($required as $field) {
            if (!isset($this->metadata[$field]) || $this->metadata[$field] === '') {
                throw new \Exception("Le champ $field est requis pour la preuve de paiement");
            }
        }

        // Validation spécifique de la date
        if (!strtotime($this->metadata['payment_date'])) {
            throw new \Exception("La date de paiement est invalide");
        }
    }

    protected function getDocumentSpecificData()
    {
        $data = [];

        switch ($this->document_type) {
            case 'loan_agreement':
                $data = [
                    'formatted_birth_date' => $this->user->birth_date?->format('d/m/Y') ?? 'Non spécifié',
                    'formatted_start_date' => Carbon::parse($this->metadata['start_date'])->format('d/m/Y'),
                    'amount_in_words' => $this->amountToWords($this->metadata['amount'])
                ];
                break;

            case 'payment_proof':
                $data = [
                    'formatted_payment_date' => Carbon::parse($this->metadata['payment_date'])->format('d/m/Y'),
                    'payment_method_label' => $this->getPaymentMethodLabel($this->metadata['payment_method'])
                ];
                break;
        }

        return $data;
    }

    protected function getPaymentMethodLabel($method)
    {
        $methods = [
            'transfer' => 'Virement bancaire',
            'check' => 'Chèque',
            'cash' => 'Espèces',
            'card' => 'Carte bancaire',
            'other' => 'Autre'
        ];

        return $methods[$method] ?? $method;
    }

    protected function amountToWords(float $amount): string
    {
        $f = new \NumberFormatter(app()->getLocale(), \NumberFormatter::SPELLOUT);
        $int = floor($amount);
        $dec = round(($amount - $int) * 100);
        
        $str = $f->format($int) . ' euro' . ($int > 1 ? 's' : '');
        
        if ($dec > 0) {
            $str .= ' ' . $f->format($dec) . ' centime' . ($dec > 1 ? 's' : '');
        }
        
        return $str;
    }

    public function getDocumentTypeNameAttribute()
    {
        $types = [
            'account_statement' => __('Relevé de compte'),
            'balance_certificate' => __('Certificat de solde'),
            'loan_agreement' => __('Contrat de prêt'),
            'anti_money_laundering' => __('Certificat de non blanchiment'),
            'payment_proof' => __('Preuve de paiement'),
        ];

        return $types[$this->document_type] ?? 'Document inconnu';
    }

    public function getDownloadUrlAttribute()
    {
        if (!$this->file_path) {
            return null;
        }

        return Storage::disk('public')->exists($this->file_path)
            ? Storage::url($this->file_path)
            : null;
    }
}