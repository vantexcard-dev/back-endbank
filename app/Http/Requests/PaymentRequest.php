<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'original_amount' => 'sometimes|numeric|min:0.01',
            'fees' => 'sometimes|numeric|min:0',
            'description' => 'required|string|max:255',
            'email' => 'required|email',
            'payment_method_type' => 'sometimes|string|in:card,ideal', // Nouvelle règle
            'billing_details' => 'sometimes|array',
            'billing_details.name' => 'sometimes|string|max:255',
            'billing_details.email' => 'sometimes|email',
            'billing_details.address' => 'sometimes|array',
            'billing_details.address.line1' => 'sometimes|string|max:255',
            'billing_details.address.city' => 'sometimes|string|max:255',
            'billing_details.address.postal_code' => 'sometimes|string|max:20',
            'billing_details.address.country' => 'sometimes|string|max:2',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Le montant est obligatoire',
            'amount.numeric' => 'Le montant doit être un nombre',
            'amount.min' => 'Le montant doit être au moins de 0.01',
            'description.required' => 'La description est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'payment_method_type.in' => 'La méthode de paiement doit être "card" ou "ideal"',
        ];
    }
}