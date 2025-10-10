<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'delivery_id';
    protected $fillable = [
        'delivery_receipt',
        'po_id',
        'supplier_id',
        'delivered_date',
        'received_by',
        'received_at',
        'status',
        'approve_by',
        'approve_at',
    ];

    protected function casts(): array
    {
        return [
            'delivered_date' => 'datetime',
            'received_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the purchase order for the delivery.
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id', 'po_id');
    }

    /**
     * Get the supplier for the delivery.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    /**
     * Get the employee who received the delivery.
     */
    public function receivedBy()
    {
        return $this->belongsTo(Employee::class, 'received_by', 'employee_id');
    }

    /**
     * Get the delivery items for the delivery.
     */
    public function deliveryItems()
    {
        return $this->hasMany(DeliveryItem::class, 'delivery_id', 'delivery_id');
    }
}
