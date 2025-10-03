<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobOrder extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'job_order_id';
    protected $fillable = [
        'jo_number',
        'customer_id',
        'order_date',
        'due_date',
        'status',
        'total_amount',
        'deposit_amount',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'datetime',
            'due_date' => 'date',
            'total_amount' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the customer that owns the job order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    /**
     * Get the employee who created the job order.
     */
    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by', 'employee_id');
    }

    /**
     * Get the job order items for the job order.
     */
    public function jobOrderItems()
    {
        return $this->hasMany(JobOrderItem::class, 'job_order_id', 'job_order_id');
    }

    /**
     * Get the stock out requests for the job order.
     */
    public function stockOutRequests()
    {
        return $this->hasMany(StockOutRequest::class, 'job_order_id', 'job_order_id');
    }
}
