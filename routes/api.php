<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api'], function($router) {
    Route::post('/login',[AuthController::class, 'login']);
    Route::post('/register',[AuthController::class, 'register']);
});

Route::prefix('category')->group(function() {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'get']);
    Route::post('/', [CategoryController::class, 'create']);
    Route::post('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'delete']);
    Route::get('/workspace/{id}', [CategoryController::class, 'getByWorkspace']);
});

Route::prefix('department')->group(function() {
    Route::get('/', [DepartmentController::class, 'index']);
    Route::get('/{id}', [DepartmentController::class, 'get']);
    Route::post('/', [DepartmentController::class, 'create']);
    Route::delete('/{id}', [DepartmentController::class, 'delete']);
});

Route::prefix('users')->group(function() {
    Route::get('/', [UsersController::class, 'index']);
    Route::delete('/{id}', [UsersController::class, 'delete']);
    Route::get('/{id}', [UsersController::class, 'get']);
    Route::post('/{id}/update', [UsersController::class, 'update']);
});

Route::prefix('workspace')->group(function() {
    Route::get('/', [WorkspaceController::class, 'index']);
    Route::get('/{id}', [WorkspaceController::class, 'get']);
    Route::post('/', [WorkspaceController::class, 'create']);
    Route::post('/{id}', [WorkspaceController::class, 'update']);
    Route::delete('/{id}', [WorkspaceController::class, 'delete']);
});

Route::prefix('issue')->group(function() {
    Route::get('/', [IssueController::class, 'index']);
    Route::get('/{id}', [IssueController::class, 'get']);
    Route::post('/', [IssueController::class, 'create']);
    Route::post('/{id}', [IssueController::class, 'update']);
    Route::delete('/{id}', [IssueController::class, 'delete']);
});