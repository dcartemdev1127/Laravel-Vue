<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api'], function($router) {
    Route::post('/login',[AuthController::class, 'login']);
    Route::post('/register',[AuthController::class, 'register']);
});