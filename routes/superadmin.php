<?php
// routes/superadmin.php
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return '<h1>Super Admin Dashboard - Welcome!</h1>';
})->name('superadmin.dashboard');