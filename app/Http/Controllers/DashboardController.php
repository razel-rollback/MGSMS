<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Example dynamic calculations
        $sales            = 832;
        $revenue          = 21300;
        $profit           = '88%';
        $cost             = 17632;
        $supplies         = 31;
        $categories       = 6;

        // Inventory summary
        $quantityInHand   = InventoryItem::sum('current_stock');
        $toReorder        = InventoryItem::whereColumn('current_stock', '<=', 're_order_stock')->count();

        // Product summary
        $totalProducts    = InventoryItem::count();
        $productCategories = InventoryItem::distinct('unit')->count('unit');

        // Top Selling & Low Stock (preview only - top 5)
        $topSelling = InventoryItem::orderByDesc('sold')->take(5)->get();
        $lowStock   = InventoryItem::where('current_stock', '<=', 15)->take(5)->get();

        return view('dashboard.dashboard', compact(
            'sales',
            'revenue',
            'profit',
            'cost',
            'supplies',
            'categories',
            'quantityInHand',
            'toReorder',
            'totalProducts',
            'productCategories',
            'topSelling',
            'lowStock'
        ));
    }

    // Full paginated Top Selling items
    public function topSellingAll()
    {
        $topSelling = InventoryItem::orderByDesc('sold')->paginate(10); // 10 per page
        return view('dashboard.top-selling', compact('topSelling'));
    }

    // Full paginated Low Stock items
    public function lowStockAll()
    {
        $lowStock = InventoryItem::where('current_stock', '<=', 15)
            ->orderBy('current_stock', 'asc')
            ->paginate(10); // 10 per page
        return view('dashboard.low-stock', compact('lowStock'));
    }
}
