<?php

namespace App\Livewire\Stocks;

use App\Models\Stock;

use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\User;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class StocksDashboard extends Component
{

    use WithPagination;

    public $stocks = [];
    public $selectedStock = null;
    public int $quantity;
    public string $transactionType;
    public $loading = false;

    protected $rules = [
        'quantity' => 'required|integer|min:1',
        'transactionType' => 'required|in:buy,sell',
    ];

    public function mount($stocks)
    {
        $this->stocks = $stocks->items();
    }

    public function render()
    {
        return view('livewire.stocks.stocks-dashboard');

    }

    public function selectStock($stockId): void
    {
        $this->loading = true;

        $this->selectedStock = Stock::find($stockId);
        $this->loading = false;
    }

    #[On('transactionCompleted')]
    public function refreshStockData()
    {
        $this->stocks = Stock::paginate(25);
    }

    public function submitTransaction()
    {
        // Refresh or fetch stocks data again
        $this->validate();

        if (!$this->selectedStock) {
            return; // Handle error - no stock selected
        }

        $formData = [
            'user_id' => auth()->id(),
            'stock_name' => $this->selectedStock['stock_name'],
            'stock_price' => $this->selectedStock['current_price'],
            'num_stock_traded' => $this->quantity,
            'transaction_total' => $this->selectedStock['current_price'] * $this->quantity,
            'transaction_type' => $this->transactionType,
        ];

        // Process transaction within a database transaction
        DB::transaction(function () use ($formData) {
            $user = User::find($formData['user_id']);

            if ($formData['transaction_type'] === 'buy') {
                $this->processBuyTransaction($formData, $user);
            } else {
                $this->processSellTransaction($formData, $user);
            }
        });

        // Optionally, reset form fields after successful submission
        $this->reset();
        $this->dispatch('transactionCompleted');
    }

    private function processBuyTransaction($formData, $user)
    {
        $transaction_total = $formData['transaction_total'];

        if ($user->balance < $transaction_total) {
            $this->addError('quantity', 'Insufficient Funds');
            return;
        }

        // Update user balance and create transaction record within transaction block
        DB::transaction(function () use ($formData, $user, $transaction_total) {
            $user->balance -= $transaction_total;
            $user->save();

            StockTransaction::create([
                'user_id' => $formData['user_id'],
                'stock_name' => $formData['stock_name'],
                'stock_price' => $formData['stock_price'],
                'num_stock_traded' => $formData['num_stock_traded'],
                'transaction_total' => $transaction_total,
                'buy' => true,
                'sell' => false,
                'timestamp' => now(),
            ]);
        });

        // Flash success message if no errors occurred
        if (!$this->hasErrors()) {
            session()->flash('transaction_success', 'Purchase transaction completed successfully.');
            $this->dispatch('transactionCompleted');
        }
    }

    private function processSellTransaction($formData, $user)
    {
        $transaction_total = $formData['transaction_total'];

        // Check if user owns enough stock to sell
        $quantity_owned = StockTransaction::where('user_id', $user->id)
            ->where('stock_name', $formData['stock_name'])
            ->sum(DB::raw("CASE WHEN buy THEN num_stock_traded ELSE -num_stock_traded END"));

        if ($quantity_owned < $formData['num_stock_traded']) {
            $this->addError('quantity', 'Insufficient Stock Owned.');
            return;
        }

        // Update user balance and create transaction record within transaction block
        DB::transaction(function () use ($formData, $user, $transaction_total) {
            $user->balance += $transaction_total;
            $user->save();

            StockTransaction::create([
                'user_id' => $formData['user_id'],
                'stock_name' => $formData['stock_name'],
                'stock_price' => $formData['stock_price'],
                'num_stock_traded' => $formData['num_stock_traded'],
                'transaction_total' => $transaction_total,
                'buy' => false,
                'sell' => true,
                'timestamp' => now(),
            ]);
        });

        // Flash success message if no errors occurred
        if (!$this->hasErrors()) {
            session()->flash('transaction_success', 'Sell transaction completed successfully.');
            $this->dispatch('transactionCompleted');
        }
    }

    public function hasErrors()
    {
        // Check if there are any validation errors
        return !empty($this->getErrorBag()->all());
    }

}
