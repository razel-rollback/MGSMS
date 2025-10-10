<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManagerDashboardController;
use App\Http\Controllers\ProductionDashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockOutRequestController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\TopSellingStockController;
use App\Http\Controllers\LowStockItemController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockInRequestController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Route for Dashboard


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Manager Dashboard
Route::get('/pending-requests', [ManagerDashboardController::class, 'index'])->name('pending.requests');
Route::patch('/delivery/{delivery_id}/approve', [ManagerDashboardController::class, 'approveDelivery'])->name('delivery.approve');
Route::patch('/delivery/{delivery_id}/disapprove', [ManagerDashboardController::class, 'disapproveDelivery'])->name('delivery.disapprove');
Route::patch('/purchase-order/{po_id}/approve', [ManagerDashboardController::class, 'approvePurchaseOrder'])->name('purchase-order.approve');
Route::patch('/purchase-order/{po_id}/disapprove', [ManagerDashboardController::class, 'disapprovePurchaseOrder'])->name('purchase-order.disapprove');
Route::patch('/stock-in/{stock_in_id}/approve', [ManagerDashboardController::class, 'approveStockIn'])->name('stock-in.approve');
Route::patch('/stock-in/{stock_in_id}/disapprove', [ManagerDashboardController::class, 'disapproveStockIn'])->name('stock-in.disapprove');
Route::patch('/stock-out/{stock_out_id}/approve', [ManagerDashboardController::class, 'approveStockOut'])->name('stock-out.approve');
Route::patch('/stock-out/{stock_out_id}/disapprove', [ManagerDashboardController::class, 'disapproveStockOut'])->name('stock-out.disapprove');
Route::patch('/stock-adjustment/{adjustment_id}/approve', [ManagerDashboardController::class, 'approveStockAdjustment'])->name('stock-adjustment.approve');
Route::patch('/stock-adjustment/{adjustment_id}/disapprove', [ManagerDashboardController::class, 'disapproveStockAdjustment'])->name('stock-adjustment.disapprove');

// Manager Stock Request Approval 
Route::patch('/request/{id}/approve', [ManagerDashboardController::class, 'approve'])->name('request.approve');
Route::patch('/request/{id}/reject', [ManagerDashboardController::class, 'reject'])->name('request.reject');


Route::get('/Productionstaff/dashboard', [ProductionDashboardController::class, 'dashboard'])
    ->name('production.dashboard');


Route::get('/Productionstaff/stock_request-index', [ProductionDashboardController::class, 'index'])
    ->name('production.index');

Route::get('/Productionstaff/stock_request-create', [ProductionDashboardController::class, 'create'])
    ->name('production.create');

Route::post('/production/request-stock', [ProductionDashboardController::class, 'store'])
    ->name('production.store');
Route::get('/production/{id}/edit', [ProductionDashboardController::class, 'edit'])->name('production.edit');
Route::put('/production/{id}', [ProductionDashboardController::class, 'update'])->name('production.update');





Route::get('/Dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::resource('purchase_order', PurchaseOrderController::class);
Route::get('/purchase_order/next-number', [PurchaseOrderController::class, 'getNextPoNumber'])->name('purchase_order.next_number');
//Route::get('/Purchase-Order', [PurchaseOrderController::class, 'index'])->name('purchase_order_index');
//Route::get('/Purchase-Order/create', [PurchaseOrderController::class, 'create'])->name('purchase_order_create');


Route::resource('delivery', DeliveryController::class);
Route::get('/Delivery/Receive', [DeliveryController::class, 'receive'])->name('delivery.receive');
Route::get('/delivery/modal/{id}', [DeliveryController::class, 'deliveryModal'])->name('delivery.modal');
Route::get('/modal/{id}', [PurchaseOrderController::class, 'mod']);


//Route::get('/Delivery', [DeliveryController::class, 'index'])->name('delivery_index');
//Route::get('/Delivery/create', [DeliveryController::class, 'create'])->name('delivery_create');

Route::resource('stock_in', StockInRequestController::class);

//Route::get('/Stock-In', [StockInController::class, 'index'])->name('stock_in_index');
//Route::get('/Stock-In/create', [StockInController::class, 'create'])->name('stock_in_create');



Route::get('/stock-out-requests', [StockOutRequestController::class, 'index'])->name('stock-out.index');
Route::get('/stock-out-requests/{stockOutRequest}', [StockOutRequestController::class, 'show'])->name('stock.out.requests.show');
Route::patch('/stock-out-requests/{stockOutRequest}/validate', [StockOutRequestController::class, 'validate'])->name('stock.out.requests.validate')->middleware('auth');
//Route::get('/Stock-Out', [StockOutController::class, 'index'])->name('stock_out_index');
//Route::get('/Stock-Out/create', [StockOutController::class, 'create'])->name('stock_out_create');



//Route::resource Stock Adjustment
Route::resource('stock_adjustments', StockAdjustmentController::class);
Route::get('/stock_adjustments/pending', [StockAdjustmentController::class, 'pending'])->name('stock_adjustments.pending');
Route::get('/stock_adjustments/{id}', [StockAdjustmentController::class, 'show'])->name('stock_adjustments.show');

//Route::resource('stock_report', StockReportController::class);
Route::get('/Stock-Report', [StockReportController::class, 'index'])->name('stock_reports_index');
Route::get('/Stock-Report/Generate', [StockReportController::class, 'generate'])->name('stock_reports_generate');

Route::resource('suppliers', SupplierController::class);
Route::resource('employees', EmployeeController::class);

//Route::resource Inventory Items
Route::resource('items', InventoryItemController::class);


//Route for Top Selling Stocks
Route::get('/dashboard/top-selling', [DashboardController::class, 'topSellingAll'])->name('dashboard.topSelling');

//Route for Low Stock Items
Route::get('/dashboard/low-stock', [DashboardController::class, 'lowStockAll'])->name('dashboard.lowStock');
