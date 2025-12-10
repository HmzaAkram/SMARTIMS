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

// Test route - keep only one specific test
Route::get('/test-company-file', function() {
    return "Company routes file is loaded!";
});

// REMOVE or COMMENT OUT these conflicting routes:
// Route::get('/test-no-middleware/{tenant}', function($tenant) {
//     return response()->json([
//         'success' => true,
//         'tenant' => $tenant,
//         'message' => 'Route without middleware works'
//     ]);
// });

// Route::prefix('{tenant}')->group(function () {
//     Route::get('/test-no-auth', function($tenant) {
//         return response()->json([
//             'success' => true,
//             'tenant' => $tenant,
//             'message' => 'Route without any middleware'
//         ]);
//     });
// });

// Route::middleware(['auth'])->prefix('{tenant}')->group(function () {
//     Route::get('/test-no-tenant-middleware', function($tenant) {
//         return response()->json([
//             'success' => true,
//             'tenant' => $tenant,
//             'message' => 'Route without tenant middleware'
//         ]);
//     });
// });

// ONLY KEEP THIS MAIN ROUTE GROUP:
Route::middleware(['auth', 'tenant'])->prefix('company/{tenant}')->group(function () {
    
    // Debug test route - keep this for testing
    Route::get('/debug', function($tenant) {
        return response()->json([
            'tenant' => $tenant,
            'message' => 'Debug route working',
            'user' => auth()->user() ? auth()->user()->email : 'Not logged in'
        ]);
    })->name('company.debug');
    
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
    'edit' => 'company.stock-movements.edit',  // Make sure this line exists
    'update' => 'company.stock-movements.update',
    'destroy' => 'company.stock-movements.destroy',
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
   // Sales routes
    Route::prefix('sales')->name('company.sales.')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('index');
        Route::get('/create', [SalesController::class, 'create'])->name('create');
        Route::post('/', [SalesController::class, 'store'])->name('store');
        Route::get('/{order}', [SalesController::class, 'show'])->name('show');
        Route::get('/{order}/edit', [SalesController::class, 'edit'])->name('edit');
        Route::put('/{order}', [SalesController::class, 'update'])->name('update');
        Route::delete('/{order}', [SalesController::class, 'destroy'])->name('destroy');
        Route::get('/{order}/print-invoice', [SalesController::class, 'printInvoice'])->name('print.invoice');
        Route::post('/{order}/update-status', [SalesController::class, 'updateStatus'])->name('update.status');
        Route::get('/report', [SalesController::class, 'report'])->name('report');
        Route::get('/items', [SalesController::class, 'getItems'])->name('items.get');
    });
    
    // Suppliers
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

// Supplier additional routes
Route::post('suppliers/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('company.suppliers.toggle-status');
Route::get('suppliers/get/list', [SupplierController::class, 'getSuppliers'])->name('company.suppliers.get');
    
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
    
    Route::post('/customers/{customer}/toggle-status', [\App\Http\Controllers\Company\CustomerController::class, 'toggleStatus'])->name('company.customers.toggle-status');
    Route::get('/customers-get', [\App\Http\Controllers\Company\CustomerController::class, 'getCustomers'])->name('company.customers.get');
    Route::get('/customers/{customer}/details', [\App\Http\Controllers\Company\CustomerController::class, 'getCustomerDetails'])->name('company.customers.details');
    // Reports
    Route::prefix('reports')->name('company.reports.')->group(function () {
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/stock-movements', [ReportController::class, 'stockMovements'])->name('stock-movements');
    });
    
    // Settings
   Route::get('settings', [SettingsController::class, 'index'])->name('company.settings');
   Route::put('settings', [SettingsController::class, 'update'])->name('company.settings.update');
// Add to routes/web.php temporarily
Route::get('/debug-items-table', function() {
    try {
        // Switch to tenant connection
        config(['database.default' => 'tenant']);
        \DB::reconnect();
        
        // Check items table structure
        $columns = \Schema::getColumnListing('items');
        
        // Also check items data
        $items = \DB::table('items')->select('id', 'name', 'sku')->limit(5)->get();
        
        return response()->json([
            'columns' => $columns,
            'sample_items' => $items,
            'has_stock_column' => in_array('stock', $columns),
            'has_quantity_column' => in_array('quantity', $columns)
        ]);
        
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});


});