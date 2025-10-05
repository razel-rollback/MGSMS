<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'po_id';
    protected $fillable = [
        'po_number',
        'supplier_id',
        'order_date',
        'expected_date',
        'total_amount',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'datetime',
            'expected_date' => 'date',
            'total_amount' => 'decimal:2',
            'approved_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchaseOrder) {
            if (empty($purchaseOrder->po_number)) {
                $purchaseOrder->po_number = self::generatePoNumber();
            }
        });
    }

    /**
     * Generate a unique purchase order number
     * Format: MGS-YYYY-NNNN (e.g., MGS-2024-0001)
     */
    public static function generatePoNumber()
    {
        $year = now()->year;

        // Get the latest PO number for the current year
        $latestPo = self::withTrashed()
            ->where('po_number', 'like', 'PO-MGS-' . $year . '-%')
            ->orderBy('po_number', 'desc')
            ->first();

        if ($latestPo) {
            // Extract the number from the latest PO and increment
            $lastNumber = (int) substr($latestPo->po_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            // First PO of the year
            $nextNumber = 1;
        }

        return 'PO-MGS-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the next PO number without creating a record
     * Useful for previewing the number before creation
     */
    public static function getNextPoNumber()
    {
        return self::generatePoNumber();
    }

    /**
     * Get the supplier that owns the purchase order.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    /**
     * Get the employee who created the purchase order.
     */
    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by', 'employee_id');
    }

    /**
     * Get the employee who approved the purchase order.
     */
    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by', 'employee_id');
    }

    /**
     * Get the purchase order items for the purchase order.
     */
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'po_id', 'po_id');
    }

    /**
     * Get the deliveries for the purchase order.
     */
    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'po_id', 'po_id');
    }

    /**
     * Get the stock in requests for the purchase order.
     */
    public function stockInRequests()
    {
        return $this->hasMany(StockInRequest::class, 'po_id', 'po_id');
    }
}
