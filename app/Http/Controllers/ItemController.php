<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::orderBy('item_id', 'desc')->paginate(10);
        return view('Item.index', compact('items'));
    }

    public function create()
    {
        return view('Item.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            're_order_stock' => 'required|integer|min:0',
            'current_stock' => 'required|integer|min:0',
        ]);

        Item::create($request->all());

        return redirect()->route('items.index')
            ->with('success', 'Item added successfully.');
    }

    public function edit(Item $item)
    {
        return view('Item.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            're_order_stock' => 'required|integer|min:0',
            'current_stock' => 'required|integer|min:0',
        ]);

        $item->update($request->all());

        return redirect()->route('items.index')
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Item deleted successfully.');
    }
}
