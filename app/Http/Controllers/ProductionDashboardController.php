<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\StockRequest;

class ProductionDashboardController extends Controller
{
    // Show the dashboard with items and requests
    public function index()
        {
            $items = InventoryItem::all();
            $requests = StockRequest::with(['item', 'requester'])
                ->where('user_id', auth()->id())
                ->latest()
                ->get();

            return view('ProductionStaff.stock_request-index', compact('items', 'requests'));
        }

    // Handle stock out request submission
    public function requestStock(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        StockRequest::create([
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        return redirect()->route('production.dashboard')
            ->with('success', 'Stock request submitted.');
    }
}