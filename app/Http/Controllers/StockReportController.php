<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\StockMovement;
use App\Models\PurchaseOrder;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StockReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function generateReport(Request $request)
    {
        $reportType = $request->report_type;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        switch ($reportType) {
            case 'current_stock':
                $data = $this->generateCurrentStockReport();
                $columns = $this->getCurrentStockColumns();
                break;

            case 'low_stock':
                $data = $this->generateLowStockReport();
                $columns = $this->getLowStockColumns();
                break;

            case 'stock_movement':
                $data = $this->generateStockMovementReport($dateFrom, $dateTo);
                $columns = $this->getStockMovementColumns();
                break;

            case 'purchase_orders':
                $data = $this->generatePurchaseOrderReport();
                $columns = $this->getPurchaseOrderColumns();
                break;

            default:
                return redirect()->back()->with('error', 'Invalid report type');
        }

        return view('reports.result', compact('data', 'columns', 'reportType'));
    }

    /**
     * Current Stock Level Report
     */
    private function generateCurrentStockReport()
    {
        return InventoryItem::with(['stockMovements' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])
            ->select('item_id', 'name', 'unit', 'current_stock', 're_order_stock')
            ->get()
            ->map(function ($item) {
                return [
                    'item_id' => $item->item_id,
                    'name' => $item->name,
                    'unit' => $item->unit,
                    'current_stock' => $item->current_stock,
                    're_order_stock' => $item->re_order_stock,
                    'status' => $item->current_stock <= $item->re_order_stock ? 'Low Stock' : 'Adequate',
                    'last_movement' => $item->stockMovements->first()?->created_at?->format('Y-m-d H:i') ?? 'No Movement'
                ];
            });
    }

    private function getCurrentStockColumns()
    {
        return [
            'item_id' => 'Item ID',
            'name' => 'Item Name',
            'unit' => 'Unit',
            'current_stock' => 'Current Stock',
            're_order_stock' => 'Re-order Level',
            'status' => 'Stock Status',
            'last_movement' => 'Last Movement'
        ];
    }

    /**
     * Low Stock Alert Report
     */
    private function generateLowStockReport()
    {
        return InventoryItem::whereColumn('current_stock', '<=', 're_order_stock')
            ->select('item_id', 'name', 'unit', 'current_stock', 're_order_stock')
            ->get()
            ->map(function ($item) {
                $shortage = $item->re_order_stock - $item->current_stock;
                return [
                    'item_id' => $item->item_id,
                    'name' => $item->name,
                    'unit' => $item->unit,
                    'current_stock' => $item->current_stock,
                    're_order_stock' => $item->re_order_stock,
                    'shortage' => $shortage > 0 ? $shortage : 0,
                    'urgency' => $shortage > ($item->re_order_stock * 0.5) ? 'High' : 'Medium'
                ];
            });
    }

    private function getLowStockColumns()
    {
        return [
            'item_id' => 'Item ID',
            'name' => 'Item Name',
            'unit' => 'Unit',
            'current_stock' => 'Current Stock',
            're_order_stock' => 'Re-order Level',
            'shortage' => 'Shortage Qty',
            'urgency' => 'Urgency Level'
        ];
    }

    /**
     * Stock Movement Report
     */
    private function generateStockMovementReport($dateFrom = null, $dateTo = null)
    {
        $query = StockMovement::with(['inventoryItem', 'creator']);

        if ($dateFrom && $dateTo) {
            $query->whereBetween('created_at', [
                Carbon::parse($dateFrom)->startOfDay(),
                Carbon::parse($dateTo)->endOfDay()
            ]);
        }

        return $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($movement) {
                return [
                    'movement_id' => $movement->movement_id,
                    'item_name' => $movement->inventoryItem->name,
                    'movement_type' => $movement->movement_type,
                    'quantity' => $movement->quantity,
                    'reference_type' => $movement->reference_type,
                    'reference_id' => $movement->reference_id,
                    'created_by' => $movement->creator->fullname ?? 'System',
                    'created_at' => $movement->created_at->format('Y-m-d H:i'),
                    'note' => $movement->note
                ];
            });
    }

    private function getStockMovementColumns()
    {
        return [
            'movement_id' => 'Movement ID',
            'item_name' => 'Item Name',
            'movement_type' => 'Type',
            'quantity' => 'Quantity',
            'reference_type' => 'Reference Type',
            'reference_id' => 'Reference ID',
            'created_by' => 'Created By',
            'created_at' => 'Date/Time',
            'note' => 'Notes'
        ];
    }

    /**
     * Purchase Order Status Report
     */
    private function generatePurchaseOrderReport()
    {
        return PurchaseOrder::with(['supplier', 'purchaseOrderItems.inventoryItem'])
            ->whereIn('status', ['pending', 'approved', 'partially_received'])
            ->get()
            ->map(function ($po) {
                $totalItems = $po->purchaseOrderItems->sum('quantity');
                $receivedItems = $po->deliveries->flatMap->deliveryItems->sum('quantity_received');

                return [
                    'po_number' => $po->po_number,
                    'supplier' => $po->supplier->name,
                    'order_date' => $po->order_date->format('Y-m-d'),
                    'expected_date' => $po->expected_date,
                    'total_items' => $totalItems,
                    'received_items' => $receivedItems,
                    'pending_items' => $totalItems - $receivedItems,
                    'status' => $po->status,
                    'delivery_status' => $po->delivery_status
                ];
            });
    }

    private function getPurchaseOrderColumns()
    {
        return [
            'po_number' => 'PO Number',
            'supplier' => 'Supplier',
            'order_date' => 'Order Date',
            'expected_date' => 'Expected Date',
            'total_items' => 'Total Items',
            'received_items' => 'Received Items',
            'pending_items' => 'Pending Items',
            'status' => 'PO Status',
            'delivery_status' => 'Delivery Status'
        ];
    }
}
