<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::middleware('token.auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/categories', [DashboardController::class, 'category'])->name('categories');
    Route::get('/products', [DashboardController::class, 'product'])->name('products');
    Route::get('/customers',[DashboardController::class,'customers'])->name('customers');
    Route::get('/stocks', [DashboardController::class, 'stock'])->name('stocks');
    Route::get('/pos', [DashboardController::class, 'pos'])->name('pos');
    Route::get('/invoices', [DashboardController::class, 'invoice'])->name('invoices');
});
