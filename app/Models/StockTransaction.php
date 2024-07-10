<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    protected $table = 'stock_transactions';
    // We use fillable here since we want mass assign
    // these fields in the table
    // Need to look into my diagram of how I have the tables setup so
    // May change later on
    protected $fillable = [
        'user_id',
        'stock_name',
        'stock_price',
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
