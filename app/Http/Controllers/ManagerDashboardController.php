<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\PurchaseOrder;
use App\Models\StockInRequest;
use App\Models\StockOutRequest;
use App\Models\StockAdjustment;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        // Fetch pending records
        $deliveries = Delivery::where('status', 'Pending')->with(['purchaseOrder', 'supplier', 'receivedBy'])->get();
        $purchaseOrders = PurchaseOrder::where('status', 'Pending')->with(['supplier'])->get();
        $stockInRequests = StockInRequest::where('status', 'Pending')->with(['purchaseOrder', 'delivery', 'requester'])->get();
        $stockOutRequests = StockOutRequest::where('status', 'Pending')->with(['jobOrder', 'requester'])->get();
        $stockAdjustments = StockAdjustment::where('status', 'Pending')->with(['inventoryItem', 'requester'])->get();

        return view('manager.manager-dashboard', compact(
            'deliveries',
            'purchaseOrders',
            'stockInRequests',
            'stockOutRequests',
            'stockAdjustments'
        ));
    }

    public function disapproveDelivery(Request $request, $delivery_id)
    {
        $delivery = Delivery::findOrFail($delivery_id);
        $delivery->update(['status' => 'Disapproved']);
        return redirect()->back()->with('success', 'Delivery disapproved successfully.');
    }

    public function approvePurchaseOrder(Request $request, $po_id)
    {
        $po = PurchaseOrder::findOrFail($po_id);
        $po->update(['status' => 'Approved']);
        return redirect()->back()->with('success', 'Purchase Order approved successfully.');
    }

    public function disapprovePurchaseOrder(Request $request, $po_id)
    {
        $po = PurchaseOrder::findOrFail($po_id);
        $po->update(['status' => 'Disapproved']);
        return redirect()->back()->with('success', 'Purchase Order disapproved successfully.');
    }

    public function approveStockIn(Request $request, $stock_in_id)
    {
        $request = StockInRequest::findOrFail($stock_in_id);
        $request->update(['status' => 'Approved']);
        return redirect()->back()->with('success', 'Stock In Request approved successfully.');
    }

    public function disapproveStockIn(Request $request, $stock_in_id)
    {
        $request = StockInRequest::findOrFail($stock_in_id);
        $request->update(['status' => 'Disapproved']);
        return redirect()->back()->with('success', 'Stock In Request disapproved successfully.');
    }

    public function approveStockOut(Request $request, $stock_out_id)
    {
        $request = StockOutRequest::findOrFail($stock_out_id);
        $request->update(['status' => 'Approved']);
        return redirect()->back()->with('success', 'Stock Out Request approved successfully.');
    }

    public function disapproveStockOut(Request $request, $stock_out_id)
    {
        $request = StockOutRequest::findOrFail($stock_out_id);
        $request->update(['status' => 'Disapproved']);
        return redirect()->back()->with('success', 'Stock Out Request disapproved successfully.');
    }

    public function approveStockAdjustment(Request $request, $adjustment_id)
    {
        $adjustment = StockAdjustment::findOrFail($adjustment_id);
        $adjustment->update(['status' => 'Approved']);
        return redirect()->back()->with('success', 'Stock Adjustment approved successfully.');
    }

    public function disapproveStockAdjustment(Request $request, $adjustment_id)
    {
        $adjustment = StockAdjustment::findOrFail($adjustment_id);
        $adjustment->update(['status' => 'Disapproved']);
        return redirect()->back()->with('success', 'Stock Adjustment disapproved successfully.');
    }
}
