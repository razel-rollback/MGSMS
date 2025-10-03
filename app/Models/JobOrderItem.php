<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobOrderItem extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'job_item_id';
    protected $fillable = [
        'job_order_id',
        'service_id',
        'item_id',
        'notes',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the job order that owns the job order item.
     */
    public function jobOrder()
    {
        return $this->belongsTo(JobOrder::class, 'job_order_id', 'job_order_id');
    }

    /**
     * Get the service for the job order item.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }

    /**
     * Get the inventory item for the job order item.
     */
    public function inventoryItem()
    {
        return $this->belongsTo(IventoryItem::class, 'item_id', 'item_id');
    }

    /**
     * Get the production tasks for the job order item.
     */
    public function productionTasks()
    {
        return $this->hasMany(ProductionTask::class, 'job_item_id', 'job_item_id');
    }

    /**
     * Get the stock out requests for the job order item.
     */
    public function stockOutRequests()
    {
        return $this->hasMany(StockOutRequest::class, 'job_item_id', 'job_item_id');
    }
}
