<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Home (Dashboard) 
Route::get('/', function () {
    return view('layouts.welcome');   
})->middleware(['auth'])->name('home');

//Authentication 
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//  Registration 
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Dashboard 
Route::get('/dashboard', function () {
    return view('layouts.welcome');   
})->middleware(['auth'])->name('dashboard');