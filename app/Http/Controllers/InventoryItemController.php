<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = InventoryItem::paginate(10);
        return view('Item.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Item.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:150',
            'unit'           => 'required|string|max:50',
            're_order_stock' => 'required|integer|min:0',
            'current_stock'  => 'required|integer|min:0',
        ]);

        InventoryItem::create($validated);

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryItem $item)
    {
        return view('Item.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventoryItem $item)
    {
        return view('Item.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryItem $item)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:150',
            'unit'           => 'required|string|max:50',
            'current_stock'  => 'required|integer|min:0',
        ]);

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryItem $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
