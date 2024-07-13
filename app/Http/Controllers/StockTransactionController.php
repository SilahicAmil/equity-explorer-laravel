<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockTransactionController extends Controller
{

    /**
     * @param  User  $user
     * @param  string  $stock_name
     * @param  int  $quantity
     * @return bool
     */
    private function userOwnsStock(User $user, string $stock_name, int $quantity) : bool
    {
        // Make this better SoonTM
        $quantity_owned = StockTransaction::where('user_id', $user->id)
                                            ->where('stock_name', $stock_name)
                                            ->sum(DB::raw("CASE WHEN buy THEN num_stock_traded ELSE -num_stock_traded END"));
        if ($quantity_owned >= $quantity) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param  Request  $request
     * @return RedirectResponse
     */
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

        // Determine the transaction type and use correct function
        $transaction_type = $request->transaction_type;

        return $transaction_type === 'buy'
            ? $this->processBuyTransaction($request)
            : $this->processSellTransaction($request);

    }

    /**
     * @param  Request  $request
     * @return RedirectResponse
     */
    private function processBuyTransaction(Request $request) : RedirectResponse
    {
        Log::error('In Process Buy');
        $user = User::find($request->user_id);
        $transaction_total = $request->num_stock_traded * $request->stock_price;

        if ($user->balance < $transaction_total) {
            return redirect()->back()->with('error', 'Insufficient Funds');
        }

        // Create a new DB transactions to update user balance and create transaction
        DB::transaction(function () use ($user, $request, $transaction_total){
            // Update the users balance
            $user->balance -= $transaction_total;
            $user->save();

            StockTransaction::create([
                'user_id' => $request->user_id,
                'stock_name' => $request->stock_name,
                'stock_price' => $request->stock_price,
                'num_stock_traded' => $request->num_stock_traded,
                'transaction_total' => $transaction_total,
                'buy' => true,
                'sell' => false,
                'timestamp' => now(),
            ]);
        });

        return redirect()->back()->with('success', 'Stock Bought Successfully');
    }

    /**
     * @param  Request  $request
     * @return RedirectResponse
     */
    private function processSellTransaction(Request $request) : RedirectResponse
    {
        Log::error('In Process Sell');
        $user = User::find($request->user_id);
        $transaction_total = $request->num_stock_traded * $request->stock_price;

        // Check if user owns stock in order to sell.
        if (!$this->userOwnsStock($user, $request->stock_name, $request->num_stock_traded)) {
            return redirect()->back()->with('error', 'You do not own this stock.');
        }

        DB::transaction(function () use ($user, $request, $transaction_total) {
            // Update user balance
            $user->balance += $transaction_total;
            $user->save();

            StockTransaction::create([
                'user_id' => $request->user_id,
                'stock_name' => $request->stock_name,
                'stock_price' => $request->stock_price,
                'num_stock_traded' => $request->num_stock_traded,
                'transaction_total' => $transaction_total,
                'buy' => false,
                'sell' => true,
                'timestamp' => now(),
            ]);
        });

        return redirect()->back()->with('success', 'Stock Sold Successfully!');
    }
}
