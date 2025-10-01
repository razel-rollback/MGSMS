<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseItem;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::latest()->paginate(10);
        return view('Stock_in.purchase_order1', compact('purchaseOrders'));
    }

    public function show($id)
    {
        $order = PurchaseOrder::findOrFail($id);
        return view('Stock_in.purchase_show', compact('order'));
    }

    public function storeBasic(Request $request)
    {
        $request->validate([
            'supplier' => 'required|string|max:255',
            'order_date' => 'required|date',
            'delivery_date' => 'required|date|after_or_equal:order_date',
            'total_amount' => 'required|numeric',
        ]);

        PurchaseOrder::create($request->all());

        return redirect()->route('purchase_orders.index')->with('success', 'Purchase Order created successfully.');
    }

    // Purchase_add1
    public function create()
    {
        return view('purchase_add1');
    }

    // Save the purchase order and items
    public function storeWithItems(Request $request)
    {
        $request->validate([
            'po_number' => 'required|unique:purchase_orders,po_number',
            'supplier_name' => 'required',
            'order_date' => 'required|date',
            'delivery_date' => 'required|date',
        ]);

        // Create purchase order
        $order = PurchaseOrder::create([
            'po_number' => $request->po_number,
            'supplier_name' => $request->supplier_name,
            'order_date' => $request->order_date,
            'delivery_date' => $request->delivery_date,
        ]);

        // Insert items if any
        if ($request->items) {
            foreach ($request->items as $item) {
                PurchaseItem::create([
                    'purchase_order_id' => $order->id,
                    'item_name' => $item['name'],
                    'unit' => $item['unit'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                ]);
            }
        }

        return redirect()->route('purchase.add')->with('success', 'Purchase Order Created Successfully');
    }
}
