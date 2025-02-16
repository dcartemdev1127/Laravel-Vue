<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api'], function($router) {
    Route::post('/login',[AuthController::class, 'login']);
    Route::post('/register',[AuthController::class, 'register']);
});

Route::prefix('category')->group(function() {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'getCategory']);
    Route::post('/', [CategoryController::class, 'create']);
    Route::delete('/{id}', [CategoryController::class, 'delete']);
});

Route::prefix('department')->group(function() {
    Route::get('/', [DepartmentController::class, 'index']);
    Route::get('/{id}', [DepartmentController::class, 'get']);
    Route::post('/', [DepartmentController::class, 'create']);
    Route::delete('/{id}', [DepartmentController::class, 'delete']);
});