<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseItem;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_number',
        'supplier',
        'order_date',
        'delivery_date',
        'total_amount',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
