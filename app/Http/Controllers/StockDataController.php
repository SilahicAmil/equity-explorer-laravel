<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Services\SupabaseService;
use Illuminate\Http\Request;

class StockDataController extends Controller
{
    public function index()
    {
        $stocks = Stock::limit(50)->get();
        return view('stocks', compact('stocks'));
    }

}
