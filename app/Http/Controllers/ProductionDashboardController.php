<?php

namespace App\Http\Controllers;

use App\Models\StockOutItem;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\StockOutRequest;
use Illuminate\Support\Facades\Auth;

class ProductionDashboardController extends Controller
{
    /**
     * Show the stock request index page with items and user's requests.
     */
    public function index()
    {
        $requests = StockOutRequest::paginate(10);



        return view('ProductionStaff.stock_request-index', compact('requests'));
    }

    /**
     * Handle stock out request submission.
     */
    public function create()
    {
        $items = InventoryItem::all(); // or your item model
        return view('ProductionStaff.stock_request-create', compact('items'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:inventory_items,item_id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Create the stock out request
        $stockOut = StockOutRequest::create([
            'requested_by' => auth()->id(),
            'requested_at' => now(),
            'status' => 'Pending',
        ]);

        // Add the requested items
        foreach ($request->items as $itemData) {
            StockOutItem::create([
                'stock_out_id' => $stockOut->stock_out_id,
                'item_id' => $itemData['item_id'],
                'quantity' => $itemData['quantity'],
                'status' => 'pending',
            ]);
        }

        return redirect()->route('production.index')->with('success', 'Stock out request submitted successfully!');
    }


    public function edit($id)
    {
        $request = StockOutRequest::with('stockOutItems.inventoryItem')->findOrFail($id);
        $items = InventoryItem::all();

        $existingItems = $request->stockOutItems->map(function ($i) {
            return [
                'item_id' => $i->item_id,
                'name' => $i->inventoryItem->name ?? 'N/A',
                'quantity' => $i->quantity,
            ];
        });

        return view('ProductionStaff.stock_request-edit', compact('request', 'items', 'existingItems'));
    }



    public function update(Request $request, $id)
    {
        $stockRequest = StockOutRequest::findOrFail($id);

        $stockRequest->update([
            'note' => $request->note,
        ]);

        // Clear old items and reinsert updated ones
        $stockRequest->stockOutItems()->delete();

        foreach ($request->items as $item) {
            $stockRequest->stockOutItems()->create([
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return redirect()->route('production.index')->with('success', 'Request updated successfully.');
    }






    public function dashboard()
    {
        return view('Productionstaff.production_dashboard');
    }
}
