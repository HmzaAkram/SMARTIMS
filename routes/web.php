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
        return redirect()->route('company.dashboard', ['tenant' => $user->tenant->slug]);
    }
    
    return redirect('/');
})->name('home');

// Include company routes
require __DIR__.'/company.php';