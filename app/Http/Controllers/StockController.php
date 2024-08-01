<?php

namespace App\Http\Controllers;

use App\Models\Stock;


class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::paginate(25);
        return view('stocks.index', compact('stocks'));
    }

}
