<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'stripe_payment_intent_id',
        'customer_email',
        'amount',
        'original_amount',
        'fees',
        'currency',
        'description',
        'status',
        'billing_details',
        'receipt_url',
        'pdf_receipt_path'
    ];

    protected $casts = [
        'billing_details' => 'array',
        'amount' => 'decimal:2',
        'original_amount' => 'decimal:2',
        'fees' => 'decimal:2',
    ];

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2, ',', ' ') . ' €';
    }

    public function getFormattedOriginalAmountAttribute()
    {
        return number_format($this->original_amount, 2, ',', ' ') . ' €';
    }

    public function getFormattedFeesAttribute()
    {
        return number_format($this->fees, 2, ',', ' ') . ' €';
    }

    public function getBillingNameAttribute()
    {
        return $this->billing_details['name'] ?? '';
    }

    public function getBillingAddressAttribute()
    {
        return $this->billing_details['address'] ?? [];
    }
}