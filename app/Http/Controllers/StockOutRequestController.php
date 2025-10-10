<?php

namespace App\Http\Controllers;

use App\Models\StockOutItem;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\StockOutRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StockOutRequestController extends Controller
{
    /**
     * Display a listing of stock out requests.
     */
    public function index(Request $request)
    {
        // Initialize query
        $query = StockOutRequest::with(['jobOrder', 'requester', 'validator', 'approver']);

        // Handle search
        if ($request->has('query') && !empty($request->query('query'))) {
            $search = $request->query('query');
            $query->where(function ($q) use ($search) {
                $q->where('stock_out_id', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('jobOrder', function ($q) use ($search) {
                        $q->where('job_order_id', 'like', "%{$search}%");
                    })
                    ->orWhereHas('requester', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%");
                    });
            });
        }

        // Paginate results
        $stockOutRequests = $query->paginate(10);

        // Return view with data
        return view('Stock_out.stock_out_requested', compact('stockOutRequests'));
    }


    public function show(StockOutRequest $stockOutRequest)
    {
        // Load relationships
        $stockOutRequest->load(['jobOrder', 'requester', 'validator', 'approver', 'stockOutItems.inventoryItem']);

        return view('Stock_out.show', compact('stockOutRequest'));
    }



    /**
     * Validate a stock out request.
     */
    public function validate(Request $request, StockOutRequest $stockOutRequest)
    {
        DB::beginTransaction();

        try {
            // Prevent re-validation
            if ($stockOutRequest->status === 'Validated') {
                return redirect()->route('stock.out.requests.show', $stockOutRequest->stock_out_id)
                    ->with('error', 'This stock out request is already validated.');
            }

            // Load related items
            $stockOutRequest->load(['stockOutItems.inventoryItem']);

            $insufficientItems = [];

            foreach ($stockOutRequest->stockOutItems as $stockOutItem) {
                $inventoryItem = InventoryItem::find($stockOutItem->item_id);

                if (!$inventoryItem) {
                    throw new \Exception("Inventory item not found for item ID: {$stockOutItem->item_id}");
                }

                $available = $inventoryItem->current_stock;
                $requested = $stockOutItem->quantity;

                if ($requested > $available) {
                    $insufficientItems[] = [
                        'item_name' => $inventoryItem->name,
                        'requested_quantity' => $requested,
                        'available_quantity' => $available,
                        'shortfall' => $requested - $available,
                    ];
                }
            }

            // Handle insufficient items
            if (!empty($insufficientItems)) {
                DB::rollBack();

                Log::warning('Stock out validation failed due to insufficient inventory', [
                    'stock_out_id' => $stockOutRequest->stock_out_id,
                    'insufficient_items' => $insufficientItems
                ]);

                return redirect()->route('stock.out.requests.show', $stockOutRequest->stock_out_id)
                    ->with('error', 'Insufficient inventory for one or more items.')
                    ->with('insufficient_items', $insufficientItems);
            }

            // ✅ Update StockOutRequest
            $stockOutRequest->update([
                'status' => 'Validated',
                'validated_by' => Auth::id(),
                'validated_at' => now(),
            ]);

            // ✅ Update StockOutItems
            $stockOutRequest->stockOutItems()->update(['status' => 'Validated']);

            // ✅ Deduct stock
            foreach ($stockOutRequest->stockOutItems as $stockOutItem) {
                $inventoryItem = InventoryItem::find($stockOutItem->item_id);
                $inventoryItem->decrement('current_stock', $stockOutItem->quantity);
            }

            DB::commit();

            Log::info('Stock out request validated successfully', [
                'stock_out_id' => $stockOutRequest->stock_out_id,
                'validated_by' => Auth::id()
            ]);

            return redirect()->route('stock.out.requests.show', $stockOutRequest->stock_out_id)
                ->with('success', 'Stock out request validated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Stock out validation error', [
                'stock_out_id' => $stockOutRequest->stock_out_id ?? null,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('stock.out.requests.show', $stockOutRequest->stock_out_id)
                ->with('error', 'Validation failed: ' . $e->getMessage());
        }
    }
}
