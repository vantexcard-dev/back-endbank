<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    /**
     * Liste tous les prêts.
     */
    public function index()
    {
        $loans = Loan::with('user')->get();
        return response()->json($loans, 200);
    }

    /**
     * Crée un nouveau prêt.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'interest_rate' => 'required|numeric|min:0',
            'repayment_years' => 'required|integer|min:1',
            'payment_date' => 'nullable|date',
            'status' => 'nullable|in:pending,approved,paid,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Calcul des intérêts totaux
        $total_interest = ($request->amount * $request->interest_rate * $request->repayment_years) / 100;

        $loan = Loan::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'interest_rate' => $request->interest_rate,
            'total_interest' => $total_interest,
            'repayment_years' => $request->repayment_years,
            'payment_date' => $request->payment_date,
            'status' => $request->status ?? 'pending',
        ]);

        return response()->json($loan, 201);
    }

    /**
     * Affiche les détails d'un prêt.
     */
    public function show($id)
    {
        $loan = Loan::with('user')->find($id);

        if (!$loan) {
            return response()->json(['message' => 'Loan not found'], 404);
        }

        return response()->json($loan, 200);
    }

    /**
     * Met à jour un prêt existant.
     */
    public function update(Request $request, $id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json(['message' => 'Loan not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'sometimes|numeric|min:1',
            'interest_rate' => 'sometimes|numeric|min:0',
            'repayment_years' => 'sometimes|integer|min:1',
            'payment_date' => 'nullable|date',
            'status' => 'nullable|in:pending,approved,paid,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('amount') || $request->has('interest_rate') || $request->has('repayment_years')) {
            $loan->total_interest = ($request->amount ?? $loan->amount) *
                                    ($request->interest_rate ?? $loan->interest_rate) *
                                    ($request->repayment_years ?? $loan->repayment_years) / 100;
        }

        $loan->update($request->all());

        return response()->json($loan, 200);
    }

    /**
     * Supprime un prêt.
     */
    public function destroy($id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json(['message' => 'Loan not found'], 404);
        }

        $loan->delete();
        return response()->json(['message' => 'Loan deleted successfully'], 200);
    }
}
