<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    // Prevent Laravel from expecting a "dashboards" table
    protected $table = null;
    public $timestamps = false;

    /**
     * Get inventory summary.
     */
    public static function inventorySummary()
    {
        return [
            'quantity_in_hand' => InventoryItem::sum('current_stock'),
            'to_reorder'       => InventoryItem::whereColumn('current_stock', '<=', 're_order_stock')->count(),
        ];
    }

    /**
     * Get product summary.
     */
    public static function productSummary()
    {
        return [
            'total_products' => InventoryItem::count(),
            'categories'     => InventoryItem::distinct('unit')->count('unit'), // example: count by unit/category
        ];
    }

    /**
     * Get top selling stock.
     */
    public static function topSelling($limit = 5)
    {
        return InventoryItem::orderByDesc('sold')->take($limit)->get();
    }

    /**
     * Get low stock items.
     */
    public static function lowStock($threshold = 15)
    {
        return InventoryItem::where('current_stock', '<=', $threshold)->get();
    }
}