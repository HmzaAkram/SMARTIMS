<?php

use App\Http\Controllers\Auth\RegisterCompanyController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\Company\DashboardController as CompanyDashboard;
use App\Http\Controllers\Company\ItemController;
use App\Http\Controllers\Company\WarehouseController;
use App\Http\Controllers\Company\StockMovementController;
use App\Http\Controllers\Report\ReportController;
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

// Tenant Routes
Route::prefix('company/{tenant}')
    ->middleware(['auth', 'tenant'])
    ->name('company.')
    ->group(function () {
        Route::get('/dashboard', [CompanyDashboard::class, 'index'])->name('dashboard');
        
        // Items Routes
        Route::resource('items', ItemController::class);
        
        // Warehouses Routes
        Route::resource('warehouses', WarehouseController::class);
        
        // Stock Movements Routes
        Route::resource('stock-movements', StockMovementController::class);
        
        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
        Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    });

// Fallback Home Route
Route::middleware(['auth'])->get('/home', function () {
    $user = auth()->user();
    
    if ($user->hasRole('super-admin')) {
        return redirect()->route('admin.dashboard');
    }
    
    if ($user->tenant) {
        return redirect()->route('company.dashboard', ['tenant' => $user->tenant->slug]);
    }
    
    return redirect('/');
})->name('home');

// Include company routes
require __DIR__.'/company.php';