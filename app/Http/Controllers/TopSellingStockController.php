<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;

class TopSellingStockController extends Controller
{
    public function index()
    {
        // Get top 5 selling items
        $topSelling = InventoryItem::topSelling(5)->get();

        return view('Stock.top_selling', compact('topSelling'));
    }
}