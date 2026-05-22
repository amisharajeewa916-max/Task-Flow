<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiTaskController;
use App\Http\Controllers\Api\ApiProjectController;

Route::prefix('v1')->group(function () {
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [ApiAuthController::class, 'logout']);

        Route::get('/tasks', [ApiTaskController::class, 'index']);
        Route::post('/tasks', [ApiTaskController::class, 'store']);
        Route::get('/tasks/{task}', [ApiTaskController::class, 'show']);
        Route::put('/tasks/{task}', [ApiTaskController::class, 'update']);
        Route::delete('/tasks/{task}', [ApiTaskController::class, 'destroy']);
        Route::patch('/tasks/{task}/complete', [ApiTaskController::class, 'complete']);

        Route::middleware('role:manager,admin')->group(function () {
            Route::get('/projects', [ApiProjectController::class, 'index']);
            Route::post('/projects', [ApiProjectController::class, 'store']);
            Route::get('/projects/{project}', [ApiProjectController::class, 'show']);
            Route::put('/projects/{project}', [ApiProjectController::class, 'update']);
            Route::delete('/projects/{project}', [ApiProjectController::class, 'destroy']);
        });
    });
});
