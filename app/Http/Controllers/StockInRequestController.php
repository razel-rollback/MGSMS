<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use App\Models\StockInRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StockInRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockIns = StockInRequest::with(['delivery.supplier', 'requester'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->get();

        return view('Stock_in.index', compact('stockIns'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $excludedDeliveries = StockInRequest::whereNotNull('delivery_id')->pluck('delivery_id')->toArray();

        $purchaseOrders = PurchaseOrder::all(['po_id', 'po_number']);
        $deliveries = Delivery::where('status', 'Approved')
            ->whereNotIn('delivery_id', $excludedDeliveries)
            ->get(['delivery_id', 'delivery_receipt']);
        $items = InventoryItem::get(['item_id', 'name']);

        return view('stock_in.create', compact('purchaseOrders', 'deliveries', 'items'));
    }






    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'delivery_id' => 'nullable|exists:deliveries,delivery_id',
            'note' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:inventory_items,item_id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Create the main stock-in request
        $stockIn = StockInRequest::create([
            'delivery_id' => $request->delivery_id ?: null,
            'requested_by' => auth()->id(),
            'requested_at' => now(),
            'note' => $request->note,
        ]);
        foreach ($validated['items'] as $itemData) {
            $stockIn->stockInItems()->create($itemData);
        }


        // Create stock-in items


        return redirect()->route('stock_in.index')->with('success', 'Stock in recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $stockIn = StockInRequest::with([
            'purchaseOrder',
            'delivery',
            'requester',
            'approver',
            'stockInItems.inventoryItem'
        ])->findOrFail($id);

        return view('Stock_in.show', compact('stockIn'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $stockIn = StockInRequest::with(['stockInItems.inventoryItem', 'delivery'])->findOrFail($id);

        if ($stockIn->status === 'Approved') {
            return redirect()->route('stock_in.index')->with('error', 'Approved Stock-In requests cannot be edited.');
        }

        $excludedDeliveries = StockInRequest::whereNotNull('delivery_id')
            ->where('stock_in_id', '!=', $id)
            ->pluck('delivery_id')
            ->toArray();

        $items = InventoryItem::all(['item_id', 'name', 'unit']);
        $deliveries = Delivery::where('status', 'Approved')
            ->whereNotIn('delivery_id', $excludedDeliveries)
            ->get(['delivery_id', 'delivery_receipt']);

        return view('Stock_in.edit', compact('stockIn', 'items', 'deliveries'));
    }
    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // ✅ Validate input
        $validated = $request->validate([
            'delivery_id' => 'nullable|exists:deliveries,delivery_id',
            'note' => 'nullable|string|max:500',
            'items' => 'required|array|min:1', // Ensure at least one item is provided
            'items.*.item_id' => 'required|exists:inventory_items,item_id', // Item must exist in inventory
            'items.*.quantity' => 'required|integer|min:1', // Quantity must be positive
            'items.*.unit_price' => 'required|numeric|min:0', // Unit price must be non-negative
        ]);
    
        DB::beginTransaction();
    
        try {
            // ✅ Find the Stock-In record
            $stockIn = StockInRequest::findOrFail($id);
    
            // ✅ Update main stock-in record
            $stockIn->update([
                'delivery_id' => $validated['delivery_id'] ?? null,
                'note' => $validated['note'] ?? null,
            ]);
    
            // ✅ Remove old stock-in items and recreate
            $stockIn->stockInItems()->delete(); // Delete existing items
    
            // ✅ Add new stock-in items
            foreach ($validated['items'] as $item) {
                $stockIn->stockInItems()->create([
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }
    
            DB::commit();
    
            return redirect()
                ->route('stock_in.index')
                ->with('success', 'Stock-In request updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Stock-In Update Error: ' . $e->getMessage());
    
            return back()
                ->withErrors(['error' => 'Failed to update Stock-In request. Please try again.'])
                ->withInput(); // Preserve user input on error
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {


        try {
            $stockIn = StockInRequest::with(['purchaseOrder', 'delivery', 'stockInItems.inventoryItem'])->findOrFail($id);

            // Prevent deletion of approved Stock-In requests
            if ($stockIn->status === 'Approved') {
                return redirect()->route('stock_in.index')->with('error', 'Approved Stock-In requests cannot be deleted.');
            }
            DB::beginTransaction();
            // Delete related stock-in items
            $stockIn->stockInItems()->delete();

            // Permanently delete the Stock-In request
            $stockIn->forceDelete();

            DB::commit();

            return redirect()->route('stock_in.index')
                ->with('success', 'Stock-In record deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error for debugging
            Log::error('Stock-In Delete Error: ' . $e->getMessage());

            return redirect()->route('stock_in.index')
                ->with('error', 'Failed to delete Stock-In record: ' . $e->getMessage());
        }
    }
}
