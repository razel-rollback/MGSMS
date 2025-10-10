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
        $adjustments = StockAdjustment::paginate(10);
        $items = InventoryItem::all();
        $employees = Employee::all();

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
    public function show($id)
    {
        $adjustment = StockAdjustment::with(['inventoryItem', 'requester'])->findOrFail($id);

        return view('Stock_adjustment.stock_adjustment_show', compact('adjustment'));
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
        if ($stockAdjustment->status === 'Approved') {
            return redirect()->route('stock_adjustments.index')
                ->with('error', 'Cannot delete an approved stock adjustment.');
        }
        $stockAdjustment->delete();

        return redirect()->route('stock_adjustments.index')
            ->with('success', 'Stock adjustment deleted successfully.');
    }
}
