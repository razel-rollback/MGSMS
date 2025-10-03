<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionTask extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'task_id';
    protected $fillable = [
        'job_item_id',
        'task_type',
        'assigned_to',
        'assigned_at',
        'started_at',
        'completed_at',
        'status',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the job order item that owns the production task.
     */
    public function jobOrderItem()
    {
        return $this->belongsTo(JobOrderItem::class, 'job_item_id', 'job_item_id');
    }

    /**
     * Get the employee assigned to the production task.
     */
    public function assignedEmployee()
    {
        return $this->belongsTo(Employee::class, 'assigned_to', 'employee_id');
    }
}
