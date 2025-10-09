<?php

namespace App\Http\Controllers;

use App\Models\StockOutRequest;
use App\Models\StockOutItem;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
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
        // Check if the request is already validated
        if ($stockOutRequest->status === 'Validated') {
            return redirect()->route('stock.out.requests.show', $stockOutRequest->stock_out_id)
                ->with('error', 'This stock out request is already validated.');
        }

        // Update the stock out request
        $stockOutRequest->update([
            'status' => 'Validated',
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);

        // Optionally update related stock out items' status
        $stockOutRequest->stockOutItems()->update(['status' => 'Validated']);

        return redirect()->route('stock.out.requests.show', $stockOutRequest->stock_out_id)
            ->with('success', 'Stock out request validated successfully.');
    }
}
