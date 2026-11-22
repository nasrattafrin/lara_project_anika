<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\UnitController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PaymentController;


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('home');
    
//Authentication 
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//  Registration 
Route::post('/register', [RegisterController::class, 'register'])->name('register');


Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('plans', PlanController::class);
    Route::resource('tenants', TenantController::class);
    // Subscriptions
    Route::resource('subscriptions', SubscriptionController::class)->except(['edit', 'update']);
    Route::post('subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');

    // Invoices
    Route::resource('invoices', InvoiceController::class)->except(['edit', 'update']);

    // Payments
    Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store']);



});
 // Press Responsible / Unit Manager
Route::middleware(['auth'])->prefix('press-responsible')->group(function () {
        Route::resource('units', UnitController::class);
 });
Route::middleware(['auth'])->prefix('unit_manager')->group(function () {
Route::get('/my-unit', [UnitController::class, 'myUnit'])->name('unit.my-unit');
});

