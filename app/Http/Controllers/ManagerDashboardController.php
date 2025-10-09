<?php

namespace App\Http\Controllers;

use App\Models\StockAdjustment;
use Illuminate\Http\Request;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        // Summary counts for dashboard cards
        $pendingCount  = StockAdjustment::where('status', 'pending')->count();
        $approvedCount = StockAdjustment::where('status', 'approved')->count();
        $rejectedCount = StockAdjustment::where('status', 'rejected')->count();

        return view('manager.manager-dashboard', compact('pendingCount', 'approvedCount', 'rejectedCount'));
    }

    public function stockRequests()
    {
        $requests = StockAdjustment::with(['inventoryItem','requester'])
                        ->orderBy('requested_at','desc')
                        ->get();

        return view('manager.stock-requests', compact('requests'));
    }

    public function approve($id)
    {
        $adjustment = StockAdjustment::with('inventoryItem')->findOrFail($id);

        $adjustment->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Update stock count
        if ($adjustment->adjustment_type === 'increase') {
            $adjustment->inventoryItem->increment('stock', $adjustment->quantity);
        } elseif ($adjustment->adjustment_type === 'decrease') {
            $adjustment->inventoryItem->decrement('stock', $adjustment->quantity);
        }

        return redirect()->route('manager.dashboard')
                         ->with('success', "Request #{$id} approved and stock updated.");
    }

    public function reject($id)
    {
        $adjustment = StockAdjustment::findOrFail($id);

        $adjustment->update([
            'status'      => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('manager.dashboard')
                         ->with('error', "Request #{$id} rejected.");
    }
}