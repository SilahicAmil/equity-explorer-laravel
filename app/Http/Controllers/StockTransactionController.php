<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class StockTransactionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // Validate the request data
        $request->validate([
            'user_id' => 'required|integer',
            'stock_name' => 'required|string|max:255',
            'stock_price' => 'required|numeric',
            'num_stock_traded' => 'required|integer',
            'transaction_total' => 'required|numeric',
            'transaction_type' => 'required|string|in:buy,sell',
        ]);

        // Determine the transaction type
        $isBuy = $request->transaction_type === 'buy';
        $isSell = $request->transaction_type === 'sell';

        $transaction_total = $request->num_stock_traded * $request->stock_price;

        // Create a new stock transaction
        StockTransaction::create([
            'user_id' => $request->user_id,
            'stock_name' => $request->stock_name,
            'stock_price' => $request->stock_price,
            'num_stock_traded' => $request->num_stock_traded,
            'transaction_total' => $transaction_total,
            'buy' => $isBuy,
            'sell' => $isSell,
            'timestamp' => now(),
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Stock transaction added successfully!');
    }

    // Create one for selling stock also. Combined for now just for testing
}
