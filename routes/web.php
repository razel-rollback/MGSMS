<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseOrderController;

Route::get('/', function () {
    return view('welcome');
});

// Resource route for Purchase Orders
Route::resource('purchase_order1', PurchaseOrderController::class);
