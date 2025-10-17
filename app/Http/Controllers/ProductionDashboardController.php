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
        $requests = StockOutRequest::orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(10);



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
        // Retrieve the stock out request with its associated items and inventory details
        $request = StockOutRequest::with('stockOutItems.inventoryItem')->findOrFail($id);

        // Retrieve all inventory items for the dropdown
        $items = InventoryItem::all();

        // Prepare existing items for the form
        $existingItems = $request->stockOutItems->map(function ($stockOutItem) {
            return [
                'item_id' => $stockOutItem->item_id,
                'name' => $stockOutItem->inventoryItem->name,
                'quantity' => $stockOutItem->quantity,
            ];
        });

        return view('ProductionStaff.stock_request-edit', compact('request', 'items', 'existingItems'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:inventory_items,item_id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $stockRequest = StockOutRequest::findOrFail($id);

        // Update the stock out request details
        $stockRequest->update([
            'note' => $request->note,
        ]);

        // Clear old items and reinsert updated ones
        $stockRequest->stockOutItems()->delete();

        foreach ($request->items as $itemData) {
            $stockRequest->stockOutItems()->create([
                'item_id' => $itemData['item_id'], // Access as an array
                'quantity' => $itemData['quantity'], // Access as an array
            ]);
        }

        return redirect()->route('production.index')->with('success', 'Request updated successfully.');
    }






    public function dashboard()
    {
        return view('Productionstaff.production_dashboard');
    }


    public function destroy($id)
    {
        $request = StockOutRequest::findOrFail($id);

        // Check if the status is "Approved"
        if ($request->status === 'Approved') {
            return redirect()->route('production.index')->with('error', 'Cannot delete an approved stock request.');
        }

        try {
            $request->delete(); // Soft delete the stock request
            return redirect()->route('production.index')->with('success', 'Stock request deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('production.index')->with('error', 'Failed to delete stock request: ' . $e->getMessage());
        }
    }
}
