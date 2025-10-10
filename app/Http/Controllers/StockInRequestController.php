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
            ->latest()
            ->get();

        return view('Stock_in.index', compact('stockIns'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $purchaseOrders = PurchaseOrder::all(['po_id', 'po_number']);
        $deliveries = Delivery::all(['delivery_id', 'delivery_receipt']);
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
    public function edit($id)
    {
        $stockIn = StockInRequest::with(['purchaseOrder', 'delivery', 'stockInItems.inventoryItem'])->findOrFail($id);

        if ($stockIn->status === 'Approved') {
            return redirect()->route('stock_in.index')->with('error', 'Approved Stock-In requests cannot be edited.');
        }
        $purchaseOrders = PurchaseOrder::all();
        $deliveries = Delivery::all();
        $items = InventoryItem::all();

        return view('Stock_in.edit', compact('stockIn', 'purchaseOrders', 'deliveries', 'items'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // ✅ Validate input
        $validated = $request->validate([
            'delivery_id' => 'nullable|exists:deliveries,delivery_id',
            'note' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:inventory_items,item_id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // ✅ Find Stock In record
            $stockIn = StockInRequest::findOrFail($id);

            // ✅ Update main stock-in record
            $stockIn->update([
                'delivery_id' => $validated['delivery_id'] ?? null,
                'note' => $validated['note'] ?? null,
                'status' => $stockIn->status, // keep current status
            ]);

            // ✅ Remove old stock-in items and recreate
            $stockIn->stockInItems()->delete();

            foreach ($validated['items'] as $item) {
                $stockIn->stockInItems()->create([
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }

            // ✅ Optional: Auto log stock movement if approved or completed
            if (in_array($stockIn->status, ['Approved', 'Completed'])) { // Fixed status check (case-sensitive)
                foreach ($validated['items'] as $item) {
                    StockMovement::create([
                        'item_id' => $item['item_id'],
                        'movement_type' => 'in',
                        'reference_type' => 'stock_in',
                        'reference_id' => $stockIn->stock_in_id,
                        'quantity' => $item['quantity'],
                        'note' => 'Stock updated from Stock-In #' . $stockIn->stock_in_id,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('stock_in.index') // Fixed typo: 'stockin.index' -> 'stock_in.index'
                ->with('success', 'Stock-In request updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Stock-In Update Error: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Failed to update Stock-In request. Please try again.']);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $stockInRequest = StockInRequest::findOrFail($id);

            if ($stockInRequest->status === 'Approved') {
                throw new \Exception('Approved Stock-In requests cannot be deleted.');
            }

            // Optional: Delete related items first (cascade)
            $stockInRequest->stockInItems()->delete();

            // Permanent delete (bypass soft delete if needed; use $stockInRequest->delete() for soft delete)
            $stockInRequest->forceDelete();

            DB::commit();

            return redirect()->route('stock_in.index')
                ->with('success', 'Stock-In record deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Stock-In Delete Error: ' . $e->getMessage());

            return redirect()->route('stock_in.index')
                ->with('error', $e->getMessage() ?? 'Failed to delete Stock-In record.');
        }
    }
}
