<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InventoryItem;
use App\Models\StockRequest;

class ProductionDashboardController extends Controller
{
    /**
     * Show the stock request index page with items and user's requests.
     */
    public function stockRequestIndex()
    {
        $items = InventoryItem::all();
        $requests = StockRequest::with(['item', 'requester'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('Productionstaff.stock_request-index', compact('items', 'requests'));
    }

    /**
     * Handle stock out request submission.
     */
    public function requestStock(Request $request)
    {
        $request->validate([
            'item_id'  => 'required|exists:inventory_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        StockRequest::create([
            'item_id'  => $request->item_id,
            'quantity' => $request->quantity,
            'user_id'  => Auth::id(),
            'status'   => 'pending',
        ]);

        return redirect()->route('production.stockRequest.index')
            ->with('success', 'Stock request submitted successfully.');
    }
}