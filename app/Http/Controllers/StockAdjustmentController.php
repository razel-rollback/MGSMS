<?php

namespace App\Http\Controllers;

use App\Models\StockAdjustment;
use App\Models\InventoryItem;
use App\Models\Employee;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
         $adjustments = \App\Models\StockAdjustment::with(['inventoryItem', 'requester', 'approver'])
        ->orderBy('created_at', 'desc')
        ->paginate(10); 

        $items = \App\Models\InventoryItem::all();
        $employees = \App\Models\Employee::all();

        return view('Stock_adjustment.stock_adjustment-index', compact('adjustments', 'items', 'employees'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = InventoryItem::all();
        $employees = Employee::all();

        return view('Stock_adjustment.stock_adjustment-add', compact('items', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id'         => 'required|exists:inventory_items,item_id',
            'requested_by'    => 'required|exists:employees,employee_id',
            'adjustment_type' => 'required|string|max:30',
            'quantity'        => 'required|integer',
            'reason'          => 'required|string|max:255',
        ]);

        StockAdjustment::create($validated);

        return redirect()->route('stock_adjustments.index')
                         ->with('success', 'Stock adjustment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockAdjustment $stockAdjustment)
    {
        $stockAdjustment->load(['inventoryItem', 'requester', 'approver']);
        return view('Stock_adjustment.stock_adjustment', compact('stockAdjustment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockAdjustment $stockAdjustment)
    {
        $items = InventoryItem::all();
        $employees = Employee::all();

        return view('Stock_adjustment.stock_adjustment-edit', compact('stockAdjustment', 'items', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockAdjustment $stockAdjustment)
    {
        $validated = $request->validate([
            'item_id'         => 'required|exists:inventory_items,item_id',
            'requested_by'    => 'required|exists:employees,employee_id',
            'adjustment_type' => 'required|string|max:30',
            'quantity'        => 'required|integer',
            'reason'          => 'required|string|max:255',
            'status'          => 'required|string|max:30',
        ]);

        $stockAdjustment->update($validated);

        return redirect()->route('stock_adjustments.index')
                         ->with('success', 'Stock adjustment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockAdjustment $stockAdjustment)
    {
        $stockAdjustment->delete();

        return redirect()->route('stock_adjustments.index')
                         ->with('success', 'Stock adjustment deleted successfully.');
    }

    public function pending()
    {
        $adjustments = StockAdjustment::with(['inventoryItem','requester'])
                        ->where('status', 'pending')
                        ->get();

        return view('Stock_adjustment.stock_adjustment-pending', compact('adjustments'));
    }

    public function approve(StockAdjustment $adjustment)
    {
        $adjustment->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Update stock count
        if ($adjustment->adjustment_type === 'increase') {
            $adjustment->inventoryItem->increment('stock', $adjustment->quantity);
        } elseif ($adjustment->adjustment_type === 'decrease') {
            $adjustment->inventoryItem->decrement('stock', $adjustment->quantity);
        }

        return back()->with('success', 'Adjustment approved and stock updated.');
    }

    public function reject(StockAdjustment $adjustment)
    {
        $adjustment->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Adjustment rejected.');
    }
}