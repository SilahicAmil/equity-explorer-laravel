<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    protected $table = 'stock_transactions';
    protected $fillable = [
        'user_id',
        'stock_name',
        'bought_price',
        'num_stock_traded',
        'transaction_total',
        'buy',
        'sell',
        'timestamp',
    ];

    // Define relationships if any
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
