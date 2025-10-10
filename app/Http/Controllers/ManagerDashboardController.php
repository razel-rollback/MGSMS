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
use Illuminate\Support\Facades\Log;

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

        // Update status to Approved
        $stockOutRequest->update([
            'status' => 'Approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Loop through the stock out items
        foreach ($stockOutRequest->stockOutItems as $stockOutItem) {
            $inventoryItem = InventoryItem::findOrFail($stockOutItem->item_id);

            // Decrement available stock
            $inventoryItem->decrement('current_stock', $stockOutItem->quantity);

            // Record movement
            StockMovement::create([
                'item_id' => $stockOutItem->item_id,
                'movement_type' => 'out',
                'quantity' => $stockOutItem->quantity,
                'reference_id' => $stockOutRequest->stock_out_id,
                'reference_type' => 'StockOutRequest',
                'created_by' => auth()->id(),
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
        DB::beginTransaction();

        try {
            $adjustment = StockAdjustment::findOrFail($adjustment_id);

            // Check if already approved
            if ($adjustment->status === 'Approved') {
                return redirect()->back()->with('error', 'This adjustment is already approved.');
            }

            // Update adjustment status
            $adjustment->update([
                'status' => 'Approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Fetch related inventory item
            $inventoryItem = InventoryItem::findOrFail($adjustment->item_id);

            // Determine action based on type
            if ($adjustment->adjustment_type === 'increase') {
                $inventoryItem->increment('current_stock', $adjustment->quantity);
                $movementType = 'in';
            } else {
                // Prevent negative stock
                if ($inventoryItem->current_stock < $adjustment->quantity) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Not enough stock to decrease {$inventoryItem->name}.");
                }

                $inventoryItem->decrement('current_stock', $adjustment->quantity);
                $movementType = 'out';
            }

            // Record the movement
            StockMovement::create([
                'item_id' => $adjustment->item_id,
                'movement_type' => $movementType,
                'quantity' => $adjustment->quantity,
                'reference_id' => $adjustment->adjustment_id,
                'reference_type' => 'StockAdjustment',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Stock Adjustment approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving stock adjustment', [
                'adjustment_id' => $adjustment_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Failed to approve stock adjustment: ' . $e->getMessage());
        }
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
