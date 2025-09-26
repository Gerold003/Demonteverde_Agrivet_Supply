<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Cashier\TransactionController;
use App\Http\Controllers\Cashier\ReceiptController;
use App\Http\Controllers\Cashier\HomeController as CashierHomeController;
use App\Http\Controllers\Helper\OrderController;
use App\Http\Controllers\Helper\HomeController as HelperHomeController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\StockInController;
use App\Http\Controllers\Inventory\StockOutController;
use App\Http\Controllers\Inventory\HomeController as InventoryHomeController;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

// Default route (redirects to login or home)
Route::get('/', function () {
    return redirect()->route('login');
});

// ================= ADMIN ROUTES =================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminHomeController::class, 'index'])->name('admin.dashboard');
    Route::get('/reports/daily', [ReportController::class, 'dailySales'])->name('admin.reports.daily');
    Route::get('/reports/weekly', [ReportController::class, 'weeklySales'])->name('admin.reports.weekly');
    Route::get('/reports/inventory', [ReportController::class, 'inventoryReport'])->name('admin.reports.inventory');
});

// ================= CASHIER ROUTES =================
Route::middleware(['auth', 'role:cashier'])->prefix('cashier')->group(function () {
    Route::get('/dashboard', [CashierHomeController::class, 'index'])->name('cashier.dashboard');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('cashier.transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('cashier.transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('cashier.transactions.store');
    Route::get('/transactions/process/{order}', [TransactionController::class, 'processOrder'])->name('cashier.transactions.process');

    Route::get('/receipt/{transaction}', [ReceiptController::class, 'show'])->name('cashier.receipt.show');
});

// ================= HELPER ROUTES =================
Route::middleware(['auth', 'role:helper'])->prefix('helper')->group(function () {
    Route::get('/dashboard', [HelperHomeController::class, 'index'])->name('helper.dashboard');

    // Order Management
    Route::get('/orders', [OrderController::class, 'index'])->name('helper.orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('helper.orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('helper.orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('helper.orders.show');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('helper.orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('helper.orders.update');
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('helper.orders.status');
    Route::post('/orders/bulk-status', [OrderController::class, 'bulkUpdateStatus'])->name('helper.orders.bulk-status');
    Route::post('/orders/{order}/send-to-cashier', [OrderController::class, 'sendToCashier'])->name('helper.orders.send-to-cashier');
    // Reports
    Route::get('/reports/daily', [OrderController::class, 'dailyReport'])->name('helper.reports.daily');

    // Product Availability
    Route::get('/products/availability', [OrderController::class, 'checkAvailability'])->name('helper.products.availability');
});

// ================= INVENTORY ROUTES =================
Route::middleware(['auth', 'role:inventory'])->prefix('inventory')->group(function () {
    Route::get('/dashboard', [InventoryHomeController::class, 'index'])->name('inventory.dashboard');

    Route::get('/products', [ProductController::class, 'index'])->name('inventory.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('inventory.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('inventory.products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('inventory.products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('inventory.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('inventory.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('inventory.products.destroy');

    Route::get('/stock-in', [StockInController::class, 'index'])->name('inventory.stock-in.index');
    Route::get('/stock-in/create', [StockInController::class, 'create'])->name('inventory.stock-in.create');
    Route::post('/stock-in', [StockInController::class, 'store'])->name('inventory.stock-in.store');

    Route::get('/stock-out', [StockOutController::class, 'index'])->name('inventory.stock-out.index');
    Route::get('/stock-out/create', [StockOutController::class, 'create'])->name('inventory.stock-out.create');
    Route::post('/stock-out', [StockOutController::class, 'store'])->name('inventory.stock-out.store');
});
