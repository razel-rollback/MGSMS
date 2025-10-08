<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\DeliveryItem;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $deliveries = PurchaseOrder::with('supplier')
            ->where('status', 'Approved')
            ->where('delivery_status', '!=', 'Fully Delivered')
            ->orderByRaw("
        CASE
            WHEN expected_date = ? THEN 1
            WHEN expected_date > ? THEN 2
            ELSE 3
        END, expected_date ASC
    ", [$today, $today])
            ->paginate(10)
            ->withQueryString();

        $delivered = Delivery::with(['supplier', 'purchaseOrder'])
            ->latest('delivered_date')
            ->paginate(10)
            ->withQueryString();
        return view('delivery.deliveries-index', compact('deliveries', 'delivered'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function showModal($id)
    {
        $delivery = Delivery::with(['supplier', 'purchaseOrder', 'deliveryItems.inventoryItem'])
            ->findOrFail($id);

        return view('delivery.partials.delivered_modal', compact('delivery'));
    }
    public function deliveryModal($id)
    {
        $delivery = Delivery::with(['supplier', 'purchaseOrder', 'deliveryItems.inventoryItem'])
            ->findOrFail($id);

        return view('delivery.delivered_modal', compact('delivery'));
    }


    public function create(Request $request)
    {
        $poId = $request->get('po_id');

        if (!$poId) {
            return redirect()->route('delivery.index')
                ->with('error', 'Purchase Order ID is required.');
        }

        $purchaseOrder = PurchaseOrder::with(['supplier', 'purchaseOrderItems.inventoryItem'])
            ->find($poId);

        if (!$purchaseOrder) {
            return redirect()->route('delivery.index')
                ->with('error', 'Purchase Order not found.');
        }

        return view('delivery.deliveries-add', compact('purchaseOrder'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $validated = $request->validate([
            'delivery_receipt' => 'required|string|max:60|unique:deliveries,delivery_receipt',
            'po_id' => 'required|exists:purchase_orders,po_id',
            'delivered_date' => 'required|date',
            'delivery_status' => 'required|in:Not Delivered,Partially Delivered,Fully Delivered',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:inventory_items,item_id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // ✅ Fetch the related Purchase Order
            $po = PurchaseOrder::findOrFail($validated['po_id']);

            // ✅ Create the Delivery record
            $delivery = Delivery::create([
                'delivery_receipt' => $validated['delivery_receipt'],
                'po_id' => $po->po_id,
                'supplier_id' => $po->supplier_id,
                'delivered_date' => $validated['delivered_date'],
                'received_by' => Auth::id() ?? null, // optional, if using authentication
                'status' => 'Pending',
            ]);

            // ✅ Insert Delivery Items
            foreach ($validated['items'] as $item) {
                DeliveryItem::create([
                    'delivery_id' => $delivery->delivery_id,
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }

            // ✅ Update the PO delivery_status field
            $po->update(['delivery_status' => $validated['delivery_status']]);

            DB::commit();

            return redirect()->route('delivery.index')
                ->with('success', 'Delivery record created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save delivery: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Delivery $delivery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Find the delivery and load its relationships
        $delivery = Delivery::with([
            'purchaseOrder.supplier',
            'purchaseOrder.purchaseOrderItems.inventoryItem',
            'deliveryItems.inventoryItem'
        ])->findOrFail($id);

        // Get the associated purchase order
        $purchaseOrder = $delivery->purchaseOrder;

        // Preload purchase order items for lookup
        $poItems = $purchaseOrder->purchaseOrderItems;

        // Load delivery items (for pre-filling the table)
        $existingItems = $delivery->deliveryItems->map(function ($item) use ($poItems) {
            // Find the matching purchase order item by item_id
            $poItem = $poItems->firstWhere('item_id', $item->item_id);

            return [
                'item_id' => $item->item_id,
                'item_name' => $item->inventoryItem->name ?? 'N/A',
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->quantity * $item->unit_price,
                // ✅ Now correctly pulling ordered quantity
                'ordered_quantity' => $poItem->quantity ?? 0,
            ];
        });


        return view('delivery.deliveries-edit', compact('delivery', 'purchaseOrder', 'existingItems'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Delivery $delivery)
    {

        $validated = $request->validate([
            'delivered_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:inventory_items,item_id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.note' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Update the delivery record
            $delivery->update([
                'delivered_date' => $validated['delivered_date'],

            ]);

            // Delete existing delivery items
            $delivery->deliveryItems()->delete();

            // Insert new delivery items
            foreach ($validated['items'] as $item) {
                DeliveryItem::create([
                    'delivery_id' => $delivery->delivery_id,
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'note' => $item['note'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('delivery.index')
                ->with('success', 'Delivery updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update delivery: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delivery $delivery)
    {
        //
    }
}
