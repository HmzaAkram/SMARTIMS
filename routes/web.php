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
require __DIR__.'/company.php';