<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOutRequest extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'stock_out_id';
    protected $fillable = [
        'job_order_id',
        'job_item_id',
        'requested_by',
        'requested_at',
        'status',
        'validated_by',
        'validated_at',
        'approved_by',
        'approved_at',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'requested_at' => 'datetime',
            'validated_at' => 'datetime',
            'approved_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the job order for the stock out request.
     */
    public function jobOrder()
    {
        return $this->belongsTo(JobOrder::class, 'job_order_id', 'job_order_id');
    }

    /**
     * Get the job order item for the stock out request.
     */
    public function jobOrderItem()
    {
        return $this->belongsTo(JobOrderItem::class, 'job_item_id', 'job_item_id');
    }

    /**
     * Get the employee who requested the stock out.
     */
    public function requester()
    {
        return $this->belongsTo(Employee::class, 'requested_by', 'employee_id');
    }

    /**
     * Get the employee who validated the stock out request.
     */
    public function validator()
    {
        return $this->belongsTo(Employee::class, 'validated_by', 'employee_id');
    }

    /**
     * Get the employee who approved the stock out request.
     */
    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by', 'employee_id');
    }

    /**
     * Get the stock out items for the stock out request.
     */
    public function stockOutItems()
    {
        return $this->hasMany(StockOutItem::class, 'stock_out_id', 'stock_out_id');
    }
}
