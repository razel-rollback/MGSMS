<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMovement extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'movement_id';
    protected $fillable = [
        'item_id',
        'movement_type',
        'reference_type',
        'reference_id',
        'quantity',
        'created_by',
        'note',
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
     * Get the inventory item for the stock movement.
     */
    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id', 'item_id');
    }

    /**
     * Get the employee who created the stock movement.
     */
    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by', 'employee_id');
    }
}
