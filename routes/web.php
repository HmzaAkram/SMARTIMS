<?php

use App\Http\Controllers\Auth\RegisterCompanyController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register-company', [RegisterCompanyController::class, 'create'])->name('register-company');
Route::post('/register-company', [RegisterCompanyController::class, 'store'])->name('register-company.post');

require __DIR__.'/auth.php';

// Super Admin Routes
Route::prefix('admin')
    ->middleware(['auth', 'role:super-admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

// Fallback Home Route
Route::middleware(['auth'])->get('/home', function () {
    $user = auth()->user();
    
    if ($user->hasRole('super-admin')) {
        return redirect()->route('admin.dashboard');
    }
    
    if ($user->tenant) {
        return redirect()->route('company.dashboard', ['tenant' => $user->tenant->domain]);
    }
    
    return redirect('/');
})->name('home');
// Test route WITHOUT any middleware
Route::get('/simple-test/{param1}/{param2}', function($param1, $param2) {
    return "Test: " . $param1 . "/" . $param2;
});

// In web.php
Route::get('/test-company-pattern/{tenant}/debug', function($tenant) {
    return "Pattern test: " . $tenant;
})->middleware(['auth', 'tenant']);

Route::get('/test-tenant-middleware/{tenant}/debug', function($tenant) {
    // Manually test tenant
    $tenantModel = \App\Models\Tenant::where('domain', $tenant)->first();
    
    if (!$tenantModel) {
        return "Tenant not found: " . $tenant;
    }
    
    return response()->json([
        'success' => true,
        'tenant' => $tenant,
        'tenant_id' => $tenantModel->id,
        'database' => $tenantModel->database,
        'message' => 'Direct route test'
    ]);
})->middleware(['auth', 'tenant']);  // Test with tenant middleware


// Add this route temporarily
Route::get('/migrate-tenant-manually', function() {
    // Get the tenant
    $tenant = \App\Models\Tenant::where('domain', 'testcompany')->first();
    
    if (!$tenant) {
        return "Tenant not found";
    }
    
    // Set the database
    config(['database.connections.tenant.database' => $tenant->database]);
    \DB::purge('tenant');
    \DB::reconnect('tenant');
    
    // Check if migrations table exists, if not create it
    try {
        \DB::connection('tenant')->statement("
            CREATE TABLE IF NOT EXISTS `migrations` (
                `id` int unsigned NOT NULL AUTO_INCREMENT,
                `migration` varchar(255) NOT NULL,
                `batch` int NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        
        // Run your specific migration
        \DB::connection('tenant')->statement("
            ALTER TABLE `items` 
            CHANGE COLUMN `quantity` `stock` INT NOT NULL DEFAULT '0';
        ");
        
        // Record the migration
        \DB::connection('tenant')->table('migrations')->insert([
            'migration' => '2025_12_09_221905_add_stock_column_to_items_table',
            'batch' => 1,
        ]);
        
        return "Migration completed successfully! Column 'quantity' renamed to 'stock'";
        
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
require __DIR__.'/company.php';