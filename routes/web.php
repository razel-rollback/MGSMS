<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\EmployeeController;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Route for Dashboard
Route::get('/Dashboard', [DashboardController::class, 'index'])->name('dashboard');



Route::resource('purchase_order', PurchaseOrderController::class);
//Route::get('/Purchase-Order', [PurchaseOrderController::class, 'index'])->name('purchase_order_index');
//Route::get('/Purchase-Order/create', [PurchaseOrderController::class, 'create'])->name('purchase_order_create');


Route::resource('delivery', DeliveryController::class);
//Route::get('/Delivery', [DeliveryController::class, 'index'])->name('delivery_index');
//Route::get('/Delivery/create', [DeliveryController::class, 'create'])->name('delivery_create');

//Route::resource('stock_in', StockInController::class);
//Route::get('/Stock-In', [StockInController::class, 'index'])->name('stock_in_index');
//Route::get('/Stock-In/create', [StockInController::class, 'create'])->name('stock_in_create');



Route::resource('stock_out', StockOutController::class);
//Route::get('/Stock-Out', [StockOutController::class, 'index'])->name('stock_out_index');
//Route::get('/Stock-Out/create', [StockOutController::class, 'create'])->name('stock_out_create');

Route::resource('stock_adjustment', StockAdjustmentController::class);

//Route::resource('stock_report', StockReportController::class);
Route::get('/Stock-Report', [StockReportController::class, 'index'])->name('stock_reports_index');
Route::get('/Stock-Report/Generate', [StockReportController::class, 'generate'])->name('stock_reports_generate');

Route::resource('suppliers', SupplierController::class);

Route::resource('items', ItemController::class);
Route::resource('employees', EmployeeController::class);
 