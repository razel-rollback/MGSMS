<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockIntItem extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'sii_id';
    protected $fillable = [
        'stock_in_id',
        'item_id',
        'quantity',
        'unit_price',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the stock in request that owns the stock in item.
     */
    public function stockInRequest()
    {
        return $this->belongsTo(StockInRequest::class, 'stock_in_id', 'stock_in_id');
    }

    /**
     * Get the inventory item for the stock in item.
     */
    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id', 'item_id');
    }
}
