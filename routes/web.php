<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseOrderController;

// Redirect root to purchase orders index
Route::get('/', function () {
    return redirect()->route('purchase_order.index');
});

// Resource route for purchase orders (CRUD)
Route::resource('purchase_order', PurchaseOrderController::class);

// Shortcut route for "Add Purchase Order" page
Route::view('/purchase_order/add_purchase_order', 'add_purchase_order')
    ->name('purchase_order.add');

