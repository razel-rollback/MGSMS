<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseOrderController;

// Redirect root to purchase orders index
Route::get('/', function () {
    return redirect()->route('purchase_order.index');
});

// route for "Add Purchase Order" 
Route::get('/purchase_order/add_purchase_order', function () {
    return view('Stock_in.add_purchase_order');
})->name('purchase_order.add');

// Received Item Page
Route::view('/purchase_order/received_item', 'Stock_in.received_item')
    ->name('purchase_order.received_item');

Route::get('/deliveries', function () {
    return view('Stock_in.deliveries');
});

// Resource route for purchase orders
Route::resource('purchase_order', PurchaseOrderController::class);

// Stock Out main page
Route::view('/stock_out', 'Stock_out.stock_out')->name('stock_out');

// Stock Out Request page
Route::view('/stock_out/request', 'Stock_out.stock_out_request')->name('stock_out.request');

// Stock Adjustment main page
Route::view('/stock_adjustment', 'Stock_adjustment.stock_adjustment')
    ->name('stock_adjustment');

// Stock Adjustment Information page
Route::view('/stock_adjustment/information', 'Stock_adjustment.stock_information')
    ->name('stock_adjustment.information');

// Stock Report main page
Route::view('/stock_report', 'Stock_reports.stock_report')
    ->name('stock_report');

// Stock Report detail
Route::view('/stock_report/view', 'Stock_reports.stock_view')
    ->name('stock_report.view');


