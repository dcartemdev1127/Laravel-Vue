<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\InboxController;
use App\Http\Controllers\Admin\PriceController;
use App\Http\Controllers\Admin\AdminChatController;
use App\Http\Controllers\Admin\UserManageController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Dashboard\BookingController;
use App\Http\Controllers\Dashboard\ChatController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ParentsController;
use App\Http\Controllers\Dashboard\PaymentsController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\TutorsController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Webhooks\PaypalController;
use Illuminate\Support\Facades\Route;

// Route::get('/', [MainController::class, 'index'])->name('home');
// Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
// Route::post('/login', [LoginController::class, 'login']);
// Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');

// Route::prefix('register')->group(function() {
//     Route::get('/', [RegisterController::class, 'showRegistrationForm'])->name('register');
// });

// Route::get('/resend-verify-email', [RegisterController::class, 'resend_verify_email'])->name('resend.verify.email');
// Route::get('/forgot-password', [ResetPasswordController::class, 'showForgotForm'])->name('forgot-password');;

// Route::get('/verify-email', [RegisterController::class, 'verify_email']);

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');