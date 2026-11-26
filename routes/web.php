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
use App\Http\Controllers\Admin\AllocationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\MachineController;

// Dashboard 
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])->name('dashboard');
Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth'])->name('home');

// Authentication
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Platform Admin only
Route::middleware(['auth', 'role:platform_admin'])->prefix('admin')->group(function () {
    Route::resource('plans', PlanController::class);
    Route::resource('tenants', TenantController::class);

    Route::resource('subscriptions', SubscriptionController::class)->except(['edit', 'update']);
    Route::post('subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])
        ->name('subscriptions.cancel');

    Route::resource('invoices', InvoiceController::class)->except(['edit', 'update']);
    Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store']);
});

// Press Responsible only
Route::middleware(['auth', 'role:press_responsible'])->prefix('press_responsible')->group(function () {
    Route::resource('units', UnitController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('machines', MachineController::class)->except(['show']);

    // Allocation
    Route::get('orders/{order}/scorecard', [AllocationController::class, 'scorecard'])->name('allocations.scorecard');
    Route::post('orders/{order}/approve', [AllocationController::class, 'approve'])->name('allocations.approve');
    Route::get('allocations/{allocation}', [AllocationController::class, 'show'])->name('allocations.show');
});

// Unit Manager only
Route::middleware(['auth', 'role:unit_manager'])->prefix('unit_manager')->group(function () {
    Route::get('/my-unit', [UnitController::class, 'myUnit'])->name('unit.my-unit');
});
// Production Jobs: press responsible, unit manager, operator
Route::middleware(['auth', 'role:press_responsible,unit_manager,operator'])
    ->prefix('production')->group(function () {
        Route::get('jobs', [JobController::class, 'index'])->name('jobs.index');
        Route::get('jobs/{id}', [JobController::class, 'show'])->name('jobs.show');
        Route::post('jobs/{id}/advance', [JobController::class, 'advance'])->name('jobs.advance');
        Route::post('jobs/{id}/rework', [JobController::class, 'rework'])->name('jobs.rework');
});