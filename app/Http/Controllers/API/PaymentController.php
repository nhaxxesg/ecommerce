<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric',
            'provider' => 'required|string',
            'provider_payment_id' => 'required|string',
            'status' => 'required|string',
            'raw_response' => 'nullable|array',
        ]);

        $payment = Payment::create([
            'order_id' => $validated['order_id'],
            'amount' => $validated['amount'],
            'provider' => $validated['provider'],
            'provider_payment_id' => $validated['provider_payment_id'],
            'status' => $validated['status'],
            'raw_response' => $validated['raw_response'] ?? null,
        ]);

        return response()->json(['payment' => $payment], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::with('order')->findOrFail($id);
        return response()->json($payment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
