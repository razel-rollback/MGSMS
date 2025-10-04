<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'item_id';
    protected $fillable = [
        'name',
        'unit',
        're_order_stock',
        'current_stock',
    ];

    protected function casts(): array
    {
        return [
            're_order_stock' => 'integer',
            'current_stock' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the job order items for the inventory item.
     */
    public function jobOrderItems()
    {
        return $this->hasMany(JobOrderItem::class, 'item_id', 'item_id');
    }

    /**
     * Get the purchase order items for the inventory item.
     */
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'item_id', 'item_id');
    }

    /**
     * Get the delivery items for the inventory item.
     */
    public function deliveryItems()
    {
        return $this->hasMany(DeliveryItem::class, 'item_id', 'item_id');
    }

    /**
     * Get the stock in items for the inventory item.
     */
    public function stockInItems()
    {
        return $this->hasMany(StockIntItem::class, 'item_id', 'item_id');
    }

    /**
     * Get the stock out items for the inventory item.
     */
    public function stockOutItems()
    {
        return $this->hasMany(StockOutItem::class, 'item_id', 'item_id');
    }

    /**
     * Get the stock adjustments for the inventory item.
     */
    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class, 'item_id', 'item_id');
    }

    /**
     * Get the stock movements for the inventory item.
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'item_id', 'item_id');
    }
}
