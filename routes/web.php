<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminOnly;
use App\Http\Controllers\ShopController;

// หน้าแรก -> หน้า “ช็อป”
Route::get('/', [ShopController::class, 'home'])->name('shop.home');


// สาธารณะ: สินค้า
Route::resource('products', ProductController::class)->only(['index','show']);

// 🔹 ตะกร้าสินค้า (สาธารณะ — มีชุดเดียวพอ)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update/{product}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{product}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
});

// 🔹 ผู้ใช้ทั่วไป: Login/Register/Logout
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 🔹 โซนสมาชิก (ต้องล็อกอิน)
Route::middleware('auth')->group(function () {
    Route::get('/account', fn () => view('account.home'))->name('account.home');
});

// 🔹 แอดมิน
Route::prefix('admin')->group(function () {
    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout',[AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(AdminOnly::class)->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('products', ProductController::class)->except(['index','show']);
    });
});
