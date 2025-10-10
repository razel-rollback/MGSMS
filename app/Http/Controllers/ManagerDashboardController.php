<?php

namespace App\Http\Controllers;


use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use App\Models\StockInRequest;
use App\Models\StockAdjustment;
use App\Models\StockOutRequest;
use Illuminate\Support\Facades\DB;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        // Fetch pending records
        $deliveries = Delivery::where('status', 'Pending')->with(['purchaseOrder', 'supplier', 'receivedBy'])->get();
        $purchaseOrders = PurchaseOrder::where('status', 'Pending')->with(['supplier'])->get();
        $stockInRequests = StockInRequest::where('status', 'Pending')->with(['purchaseOrder', 'delivery', 'requester'])->get();
        $stockOutRequests = StockOutRequest::where('status', 'Validated')->with(['jobOrder', 'requester'])->get();
        $stockAdjustments = StockAdjustment::where('status', 'Pending')->with(['inventoryItem', 'requester'])->get();

        return view('manager.manager-dashboard', compact(
            'deliveries',
            'purchaseOrders',
            'stockInRequests',
            'stockOutRequests',
            'stockAdjustments'
        ));
    }

    public function approveDelivery(Request $request, $delivery_id)
    {
        $delivery = Delivery::findOrFail($delivery_id);
        $delivery->update([
            'status' => 'Approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Delivery approved successfully.');
    }

    public function disapproveDelivery(Request $request, $delivery_id)
    {
        $delivery = Delivery::findOrFail($delivery_id);
        $delivery->update([
            'status' => 'Disapproved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Delivery disapproved successfully.');
    }

    public function approvePurchaseOrder(Request $request, $po_id)
    {
        $po = PurchaseOrder::findOrFail($po_id);
        $po->update([
            'status' => 'Approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Purchase Order approved successfully.');
    }

    public function disapprovePurchaseOrder(Request $request, $po_id)
    {
        $po = PurchaseOrder::findOrFail($po_id);
        $po->update([
            'status' => 'Disapproved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Purchase Order disapproved successfully.');
    }

    public function approveStockIn(Request $request, $stock_in_id)
    {
        try {
            DB::beginTransaction();

            // Find the StockInRequest
            $stockInRequest = StockInRequest::findOrFail($stock_in_id);

            // Ensure the request is still pending
            if ($stockInRequest->status !== 'Pending') {
                throw new \Exception('Stock In Request is not in Pending status.');
            }

            // Update StockInRequest status
            $stockInRequest->update([
                'status' => 'Approved',
                'approved_by' => auth()->id(), // Assumes auth()->id() is the employee_id
                'approved_at' => now(),
            ]);

            // get all quantity and update inventory
            foreach ($stockInRequest->stockInItems as $stockInItem) {
                $inventoryItem = InventoryItem::findOrFail($stockInItem->item_id);
                $inventoryItem->increment('current_stock', $stockInItem->quantity);
                echo $inventoryItem;
                StockMovement::create([
                    'item_id' => $stockInItem->item_id,
                    'movement_type' => 'in',
                    'quantity' => $stockInItem->quantity,
                    'reference_id' => $stockInRequest->stock_in_id,
                    'reference_type' => 'StockInRequest',
                    'created_by' => $stockInRequest->requester->user->id,
                ]);
            }



            DB::commit();

            return redirect()->back()->with('success', 'Stock In Request approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to approve Stock In Request: ' . $e->getMessage());
        }
    }

    public function disapproveStockIn(Request $request, $stock_in_id)
    {
        $request = StockInRequest::findOrFail($stock_in_id);
        $request->update([
            'status' => 'Disapproved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Stock In Request disapproved successfully.');
    }

    public function approveStockOut(Request $request, $stock_out_id)
    {
        $stockOutRequest = StockOutRequest::findOrFail($stock_out_id);
        $stockOutRequest->update([
            'status' => 'Approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // get all quantity and update inventory
        foreach ($stockOutRequest->stockInItems as $stockInItem) {
            $inventoryItem = InventoryItem::findOrFail($stockInItem->item_id);
            $inventoryItem->decrement('current_stock', $stockOutRequest->quantity);


            StockMovement::create([
                'item_id' => $stockInItem->item_id,
                'movement_type' => 'out',
                'quantity' => $stockInItem->quantity,
                'reference_id' => $stockOutRequest->stock_in_id,
                'reference_type' => 'StockOutRequest',
                'created_by' => $stockOutRequest->requester->user->id,
            ]);
        }

        return redirect()->back()->with('success', 'Stock Out Request approved successfully.');
    }

    public function disapproveStockOut(Request $request, $stock_out_id)
    {
        $request = StockOutRequest::findOrFail($stock_out_id);
        $request->update([
            'status' => 'Disapproved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Stock Out Request disapproved successfully.');
    }

    public function approveStockAdjustment(Request $request, $adjustment_id)
    {
        $adjustment = StockAdjustment::findOrFail($adjustment_id);
        $adjustment->update([
            'status' => 'Approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        // get all quantity and update inventory
        foreach ($adjustment->stockInItems as $stockInItem) {
            $inventoryItem = InventoryItem::findOrFail($stockInItem->item_id);

            if ($adjustment->adjustment_type == 'increase') {
                $inventoryItem->increment('current_stock', $stockInItem->quantity);
            } else {
                $inventoryItem->decrement('current_stock', $stockInItem->quantity);
            }


            StockMovement::create([
                'item_id' => $stockInItem->item_id,
                'movement_type' => 'out',
                'quantity' => $stockInItem->quantity,
                'reference_id' => $adjustment->stock_in_id,
                'reference_type' => 'StockOutRequest',
                'created_by' => $adjustment->requester->user->id,
            ]);
        }
        return redirect()->back()->with('success', 'Stock Adjustment approved successfully.');
    }

    public function disapproveStockAdjustment(Request $request, $adjustment_id)
    {
        $adjustment = StockAdjustment::findOrFail($adjustment_id);
        $adjustment->update([
            'status' => 'Disapproved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Stock Adjustment disapproved successfully.');
    }
}
