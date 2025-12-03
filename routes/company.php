<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Company\ItemController;
use App\Http\Controllers\Company\CategoryController;
use App\Http\Controllers\Company\WarehouseController;
use App\Http\Controllers\Company\StockMovementController;
use App\Http\Controllers\Company\OrderController;
use App\Http\Controllers\Company\SalesController;
use App\Http\Controllers\Company\SupplierController;
use App\Http\Controllers\Company\CustomerController;
use App\Http\Controllers\Company\ReportController;
use App\Http\Controllers\Company\SettingsController;

Route::middleware(['auth', 'tenant'])->prefix('{tenant}')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('company.dashboard');
    
    // Items (Inventory)
    Route::resource('items', ItemController::class)->names([
        'index' => 'company.items.index',
        'create' => 'company.items.create',
        'store' => 'company.items.store',
        'show' => 'company.items.show',
        'edit' => 'company.items.edit',
        'update' => 'company.items.update',
        'destroy' => 'company.items.destroy',
    ]);
    
    // Categories
    Route::resource('categories', CategoryController::class)->names([
        'index' => 'company.categories.index',
        'create' => 'company.categories.create',
        'store' => 'company.categories.store',
        'show' => 'company.categories.show',
        'edit' => 'company.categories.edit',
        'update' => 'company.categories.update',
        'destroy' => 'company.categories.destroy',
    ]);
    
    // Warehouses
    Route::resource('warehouses', WarehouseController::class)->names([
        'index' => 'company.warehouses.index',
        'create' => 'company.warehouses.create',
        'store' => 'company.warehouses.store',
        'show' => 'company.warehouses.show',
        'edit' => 'company.warehouses.edit',
        'update' => 'company.warehouses.update',
        'destroy' => 'company.warehouses.destroy',
    ]);
    
    // Stock Movements
    Route::resource('stock-movements', StockMovementController::class)->names([
        'index' => 'company.stock-movements.index',
        'create' => 'company.stock-movements.create',
        'store' => 'company.stock-movements.store',
        'show' => 'company.stock-movements.show',
    ]);
    
    // Orders
    Route::resource('orders', OrderController::class)->names([
        'index' => 'company.orders.index',
        'create' => 'company.orders.create',
        'store' => 'company.orders.store',
        'show' => 'company.orders.show',
        'edit' => 'company.orders.edit',
        'update' => 'company.orders.update',
        'destroy' => 'company.orders.destroy',
    ]);
    
    // Sales
    Route::get('sales', [SalesController::class, 'index'])->name('company.sales.index');
    
    // Suppliers
    Route::resource('suppliers', SupplierController::class)->names([
        'index' => 'company.suppliers.index',
        'create' => 'company.suppliers.create',
        'store' => 'company.suppliers.store',
        'show' => 'company.suppliers.show',
        'edit' => 'company.suppliers.edit',
        'update' => 'company.suppliers.update',
        'destroy' => 'company.suppliers.destroy',
    ]);
    
    // Customers
    Route::resource('customers', CustomerController::class)->names([
        'index' => 'company.customers.index',
        'create' => 'company.customers.create',
        'store' => 'company.customers.store',
        'show' => 'company.customers.show',
        'edit' => 'company.customers.edit',
        'update' => 'company.customers.update',
        'destroy' => 'company.customers.destroy',
    ]);
    
    // Reports
    Route::prefix('reports')->name('company.reports.')->group(function () {
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/stock-movements', [ReportController::class, 'stockMovements'])->name('stock-movements');
    });
    
    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('company.settings');
    Route::put('settings', [SettingsController::class, 'update'])->name('company.settings.update');
});