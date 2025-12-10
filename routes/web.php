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
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Companies
        Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
        Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
        Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
        Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
        Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
        Route::put('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
        Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
        Route::post('/companies/{company}/suspend', [CompanyController::class, 'suspend'])->name('companies.suspend');
        Route::post('/companies/{company}/activate', [CompanyController::class, 'activate'])->name('companies.activate');
        Route::post('/companies/{company}/reset-trial', [CompanyController::class, 'resetTrial'])->name('companies.reset-trial');
        Route::get('/companies/export', [CompanyController::class, 'export'])->name('companies.export');
        
        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        // Subscriptions
        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::get('/subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
        Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
        Route::get('/subscriptions/{subscription}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
        Route::get('/subscriptions/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
        Route::put('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
        Route::delete('/subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
        Route::post('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
        Route::post('/subscriptions/{subscription}/renew', [SubscriptionController::class, 'renew'])->name('subscriptions.renew');
        
        // Payments
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
        Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
        Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
        Route::post('/payments/{payment}/mark-paid', [PaymentController::class, 'markAsPaid'])->name('payments.mark-paid');
        
        // Analytics
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
        
        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
        
        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/backup', [SettingController::class, 'backupDatabase'])->name('settings.backup');
        Route::post('/settings/clear-cache', [SettingController::class, 'clearCache'])->name('settings.clear-cache');
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