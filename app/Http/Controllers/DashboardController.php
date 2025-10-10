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
        //$topSelling = InventoryItem::orderByDesc('sold')->take(5)->get();
        $lowStock = InventoryItem::whereColumn('current_stock', '<=', 're_order_stock')
            ->orderBy('current_stock', 'asc')
            ->paginate(10);



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
            //'topSelling',
            'lowStock'
        ));
    }

    // Full paginated Top Selling items
    public function topSellingAll()
    {
        $topSelling = InventoryItem::orderByDesc('sold')->paginate(10); // 10 per page
        return view('dashboard.top-selling', compact('topSelling'));
    }

    public function lowStockAll()
    {
        $lowStock = InventoryItem::select('*')
            ->selectRaw("
            CASE
                WHEN current_stock <= re_order_stock THEN 1
                WHEN current_stock <= re_order_stock * 1.5 THEN 2
                ELSE 3
            END as status_priority
        ")
            ->orderBy('status_priority', 'asc')
            ->orderBy('current_stock', 'asc')
            ->paginate(10); // 10 per page

        return view('dashboard.low-stock', ['inventoryItems' => $lowStock]);
    }
}
