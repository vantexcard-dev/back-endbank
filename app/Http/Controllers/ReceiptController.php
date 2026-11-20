<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function show($paymentId)
    {
        $payment = Payment::where('payment_id', $paymentId)->firstOrFail();
        
        return view('receipts.receipt', [
            'payment' => $payment,
            'date' => now()->format('d/m/Y'),
            'receipt_number' => 'RC-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT)
        ]);
    }
}