<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;

class LowStockItemController extends Controller
{
    public function index()
    {
        // Get items with stock <= 15
        $lowStock = InventoryItem::lowStock(15)->get();

        return view('Stock.low_stock', compact('lowStock'));
    }
}