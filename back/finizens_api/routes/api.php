<?php

use App\Http\Controllers\Api\Order\CreateOrderController;
use App\Http\Controllers\Api\Order\UpdateOrderStatusController;
use App\Http\Controllers\Api\Portfolio\FindPortfolioController;
use App\Http\Controllers\Api\Portfolio\GetAllPortfolioController;
use App\Http\Controllers\Api\Portfolio\SetPortfolioController;
use Illuminate\Support\Facades\Route;

Route::prefix('portfolios')->group(function () {
    Route::get('/', GetAllPortfolioController::class);
    Route::get('{id}', FindPortfolioController::class);
    Route::put('{id}', SetPortfolioController::class);
});

Route::prefix('orders')->group(function () {
    Route::post('/', CreateOrderController::class);
    Route::patch('{id}', UpdateOrderStatusController::class);
});
