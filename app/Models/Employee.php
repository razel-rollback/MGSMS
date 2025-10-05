<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employees';
    protected $primaryKey = 'employee_id';
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'phone',
        'email',
        'is_active',
    ];


    protected $casts = [
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Employee belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relationship examples for other modules
     */
    public function createdJobOrders()
    {
        return $this->hasMany(JobOrder::class, 'created_by', 'employee_id');
    }

    public function createdPurchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'created_by', 'employee_id');
    }

    public function approvedPurchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'approved_by', 'employee_id');
    }

    public function productionTasks()
    {
        return $this->hasMany(ProductionTask::class, 'assigned_to', 'employee_id');
    }

    public function receivedDeliveries()
    {
        return $this->hasMany(Delivery::class, 'received_by', 'employee_id');
    }

    public function requestedStockIns()
    {
        return $this->hasMany(StockInRequest::class, 'requested_by', 'employee_id');
    }

    public function approvedStockIns()
    {
        return $this->hasMany(StockInRequest::class, 'approved_by', 'employee_id');
    }

    public function requestedStockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class, 'requested_by', 'employee_id');
    }

    public function approvedStockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class, 'approved_by', 'employee_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'created_by', 'employee_id');
    }
}
