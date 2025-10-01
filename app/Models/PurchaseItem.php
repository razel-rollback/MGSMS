<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id', 'item_name', 'unit', 'quantity', 'unit_price', 'subtotal'
    ];

    public function order()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
