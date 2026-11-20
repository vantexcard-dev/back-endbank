<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    public function generateReceipt(Payment $payment)
    {
        try {
            $data = [
                'payment' => $payment,
                'date' => now()->format('d/m/Y'),
                'receipt_number' => 'RC-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT)
            ];

            $pdf = PDF::loadView('receipts.receipt', $data);
            
            // Configuration du PDF
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);

            $filename = 'receipt-' . $payment->payment_id . '.pdf';
            $filePath = 'receipts/' . $filename;

            // S'assurer que le dossier existe
            if (!Storage::disk('public')->exists('receipts')) {
                Storage::disk('public')->makeDirectory('receipts');
            }

            // Sauvegarder le PDF
            Storage::disk('public')->put($filePath, $pdf->output());

            // Vérifier que le fichier a été créé
            if (!Storage::disk('public')->exists($filePath)) {
                throw new \Exception('Le fichier PDF n\'a pas été créé');
            }

            return [
                'success' => true,
                'file_path' => $filePath,
                'file_url' => Storage::disk('public')->url($filePath),
                'absolute_path' => storage_path('app/public/' . $filePath)
            ];

        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}