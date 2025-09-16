<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->name('auth.')
    ->group(static function (): void {
        Route::post('login', [AuthController::class, 'login'])
            ->withoutMiddleware('auth:api')
            ->name('login');

        Route::post('register', [AuthController::class, 'register'])
            ->withoutMiddleware('auth:api')
            ->name('register');

        Route::middleware('auth:api')
            ->group(static function (): void {
                Route::post('logout', [AuthController::class, 'logout'])
                    ->name('logout');
                Route::post('refresh', [AuthController::class, 'refresh'])
                    ->name('refresh');
            });

    });

Route::apiResource('products', ProductController::class)
    ->middleware('auth:api');
