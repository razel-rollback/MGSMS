<?php

namespace App\Http\Controllers;


use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;


class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        // Build the query
        $query = PurchaseOrder::with('supplier')->latest();

        // Apply search if provided
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('po_number', 'like', "%{$search}%")
                ->orWhereHas('supplier', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        // Paginate with query string so search stays on next/prev
        $purchaseOrders = $query->paginate(10)->withQueryString();

        return view('purchase_order.purchase_order-index', compact('purchaseOrders'));
    }

    // Purchase_add1
    public function create()
    {
        $suppliers = Supplier::all();
        $items = InventoryItem::all();
        $nextPoNumber = PurchaseOrder::getNextPoNumber();
        return view('purchase_order.purchase_order-create', compact('suppliers', 'items', 'nextPoNumber'));
    }



    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::with([
            'supplier',
            'purchaseOrderItems.inventoryItem'
        ])->findOrFail($id);

        return response()->json($purchaseOrder);
    }


    // Save the purchase order and items
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'   => 'required|exists:suppliers,supplier_id',
            'order_date'    => 'required|date',
            'expected_date' => 'required|date|after_or_equal:order_date',
        ]);

        DB::beginTransaction();

        try {
            // Create purchase order (PO number auto-generated)
            $order = PurchaseOrder::create([
                'supplier_id'   => $request->supplier_id,
                'order_date'    => $request->order_date,
                'expected_date' => $request->expected_date,
                'total_amount'  => 0,
                'status'        => 'Pending',
                'created_by' => auth()->user()->id,

            ]);

            // Insert items if any
            $totalAmount = 0;

            if ($request->items) {
                foreach ($request->items as $item) {
                    $subtotal = $item['quantity'] * $item['unit_price'];
                    $totalAmount += $subtotal;

                    PurchaseOrderItem::create([
                        'po_id'      => $order->po_id,
                        'item_id'    => $item['item_id'],
                        'quantity'   => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        //remove this line if subtotal is generated column
                        'subtotal'   => $subtotal,
                    ]);
                }

                // Update total amount of the purchase order
                $order->update(['total_amount' => $totalAmount]);
            }

            DB::commit();

            return redirect()
                ->route('purchase_order.index')
                ->with('success', 'Purchase Order Created Successfully');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create Purchase Order: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $purchaseOrder = PurchaseOrder::with(['supplier', 'purchaseOrderItems.inventoryItem'])->findOrFail($id);
        if ($purchaseOrder->status == 'Approved') {
            return back()->with('error', 'Cannot edit an approved purchase order.');
        }

        $suppliers = Supplier::all();
        $items = InventoryItem::all();

        return view('purchase_order.purchase_order-edit', compact('purchaseOrder', 'suppliers', 'items'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'supplier_id' => 'required|exists:suppliers,supplier_id',
                'expected_date' => 'required|date|after:today',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|exists:inventory_items,item_id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit_price' => 'required|numeric|min:0.01',
            ]);

            $purchaseOrder = PurchaseOrder::findOrFail($id);

            // Check if purchase order can be edited (not approved or delivered)
            if ($purchaseOrder->status === 'approved' || $purchaseOrder->status === 'delivered') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Cannot edit approved or delivered purchase orders.');
            }

            DB::beginTransaction();

            // Update purchase order details
            $purchaseOrder->update([
                'supplier_id' => $request->supplier_id,
                'expected_date' => $request->expected_date,
            ]);

            // Delete existing items
            $purchaseOrder->purchaseOrderItems()->delete();

            // Add new items
            $totalAmount = 0;
            foreach ($request->items as $itemData) {
                $subtotal = $itemData['quantity'] * $itemData['unit_price'];
                $totalAmount += $subtotal;

                PurchaseOrderItem::create([
                    'po_id' => $purchaseOrder->po_id,
                    'item_id' => $itemData['item_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'subtotal' => $subtotal,
                ]);
            }

            // Update total amount
            $purchaseOrder->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('purchase_order.index')
                ->with('success', 'Purchase Order updated successfully!');
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update Purchase Order: ' . $e->getMessage());
        }
    }

    public function mod($id)
    {
        $purchaseOrder = PurchaseOrder::with(['supplier', 'purchaseOrderItems.inventoryItem'])
            ->findOrFail($id);

        return view('purchase_order.purchase_order-approve', compact('purchaseOrder'));
    }
    public function destroy($id)
    {
        try {
            $order = PurchaseOrder::findOrFail($id);

            //  Only allow deletion if user is a Manager
            //if (auth()->user()->role !== 'Manager') {
            //  return back()->with('error', 'Unauthorized action.');
            //}

            // Prevent deleting approved orders
            if ($order->status === 'Approved') {
                return back()->with('error', 'Cannot delete an approved purchase order.');
            }

            //  Proceed with soft delete
            $order->delete();

            return redirect()->route('purchase-orders.index')
                ->with('success', 'Purchase Order deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete: ' . $e->getMessage());
        }
    }
    /**
     * Get the next PO number for preview
     */
    public function getNextPoNumber()
    {
        return response()->json([
            'po_number' => PurchaseOrder::getNextPoNumber()
        ]);
    }
}
