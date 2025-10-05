<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOutItem extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'soi_id';
    protected $fillable = [
        'stock_out_id',
        'item_id',
        'quantity',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the stock out request that owns the stock out item.
     */
    public function stockOutRequest()
    {
        return $this->belongsTo(StockOutRequest::class, 'stock_out_id', 'stock_out_id');
    }

    /**
     * Get the inventory item for the stock out item.
     */
    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id', 'item_id');
    }
}
