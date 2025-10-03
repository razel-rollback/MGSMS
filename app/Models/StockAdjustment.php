<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockAdjustment extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'adjustment_id';
    protected $fillable = [
        'item_id',
        'requested_by',
        'requested_at',
        'status',
        'approved_by',
        'approved_at',
        'adjustment_type',
        'quantity',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'requested_at' => 'datetime',
            'approved_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the inventory item for the stock adjustment.
     */
    public function inventoryItem()
    {
        return $this->belongsTo(IventoryItem::class, 'item_id', 'item_id');
    }

    /**
     * Get the employee who requested the stock adjustment.
     */
    public function requester()
    {
        return $this->belongsTo(Employee::class, 'requested_by', 'employee_id');
    }

    /**
     * Get the employee who approved the stock adjustment.
     */
    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by', 'employee_id');
    }
}
