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
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\TopSellingStockController;
use App\Http\Controllers\LowStockItemController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockInRequestController;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});


// Route for Dashboard
Route::get('/Dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Login Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Logout Route
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login')->with('success', 'You have been logged out.');
})->name('logout');

// Manager Dashboard
Route::prefix('manager')->group(function () {
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('manager.dashboard');
    Route::get('/stock-requests', [ManagerDashboardController::class, 'stockRequests'])->name('manager.stock.requests');
    Route::post('/request/{id}/approve', [ManagerDashboardController::class, 'approve'])->name('request.approve');
    Route::post('/request/{id}/reject', [ManagerDashboardController::class, 'reject'])->name('request.reject');
});


// Production Staff Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/production/dashboard', [ProductionDashboardController::class, 'index'])
        ->name('production.dashboard');

    Route::post('/production/request-stock', [ProductionDashboardController::class, 'requestStock'])
        ->name('production.requestStock');
});



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



Route::resource('stock_out', StockOutController::class);
//Route::get('/Stock-Out', [StockOutController::class, 'index'])->name('stock_out_index');
//Route::get('/Stock-Out/create', [StockOutController::class, 'create'])->name('stock_out_create');



//Route::resource Stock Adjustment
Route::resource('stock_adjustments', StockAdjustmentController::class);
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

// Manager Stock Request Approval 
Route::patch('/request/{id}/approve', [ManagerDashboardController::class, 'approve'])->name('request.approve');
Route::patch('/request/{id}/reject', [ManagerDashboardController::class, 'reject'])->name('request.reject');

// Production Staff Stock Out Request View
Route::get('/production/stock-request', [ProductionDashboardController::class, 'createRequest'])->name('production.stockRequest.create');
Route::get('/Productionstaff/stock_request-index', [ProductionDashboardController::class, 'stockRequestIndex'])
    ->name('production.stockRequest.index');


// Submit Stock Out Request
Route::post('/production/stock-request', [ProductionDashboardController::class, 'storeRequest'])->name('production.stockRequest.store');
