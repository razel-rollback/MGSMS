<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $primaryKey = 'item_id'; // since you're using item_id instead of id

    protected $fillable = [
        'name',
        'unit',
        're_order_stock',
        'current_stock',
    ];
}
