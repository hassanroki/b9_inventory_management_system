<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\StockController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use function Pest\Laravel\get;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    // Registration
    Route::post('/register', [AuthController::class, 'register']);
    // Login
    Route::post('/login', [AuthController::class, 'login']);


    // Authenticate Route
    Route::middleware('auth:sanctum')->group(function () {
        // Logout
        Route::post('/logout', [AuthController::class, 'logout']);

        // Category Route
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::get('/categories/{id}', [CategoryController::class, 'show']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        // Product Route
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::get('/products/{id}', [ProductController::class, 'show']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // Stock Movements Route
        Route::get('/stocks', [StockController::class, 'index']);
        Route::post('/stocks', [StockController::class, 'stockIn']);
        Route::post('/stocks/adjustment', [StockController::class, 'stockAdjustment']);
    });
});
