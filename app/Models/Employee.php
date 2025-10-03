<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'employee_id';
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'phone',
        'email',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the employee.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the job orders created by the employee.
     */
    public function createdJobOrders()
    {
        return $this->hasMany(JobOrder::class, 'created_by', 'employee_id');
    }

    /**
     * Get the purchase orders created by the employee.
     */
    public function createdPurchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'created_by', 'employee_id');
    }

    /**
     * Get the purchase orders approved by the employee.
     */
    public function approvedPurchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'approved_by', 'employee_id');
    }

    /**
     * Get the production tasks assigned to the employee.
     */
    public function productionTasks()
    {
        return $this->hasMany(ProductionTask::class, 'assigned_to', 'employee_id');
    }

    /**
     * Get the deliveries received by the employee.
     */
    public function receivedDeliveries()
    {
        return $this->hasMany(Delivery::class, 'received_by', 'employee_id');
    }

    /**
     * Get the stock in requests requested by the employee.
     */
    public function requestedStockIns()
    {
        return $this->hasMany(StockInRequest::class, 'requested_by', 'employee_id');
    }

    /**
     * Get the stock in requests approved by the employee.
     */
    public function approvedStockIns()
    {
        return $this->hasMany(StockInRequest::class, 'approved_by', 'employee_id');
    }

    /**
     * Get the stock adjustments requested by the employee.
     */
    public function requestedStockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class, 'requested_by', 'employee_id');
    }

    /**
     * Get the stock adjustments approved by the employee.
     */
    public function approvedStockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class, 'approved_by', 'employee_id');
    }

    /**
     * Get the stock movements created by the employee.
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'created_by', 'employee_id');
    }
}
