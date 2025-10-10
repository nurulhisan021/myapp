<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;

// หน้าแรก (ช็อป)
Route::get('/', [ShopController::class, 'home'])->name('shop.home');

// สินค้าสาธารณะ
Route::resource('products', ProductController::class)->only(['index','show']);

// ตะกร้า
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update/{product}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{product}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
});

// Auth (ระบบเดียว)
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// โซนสมาชิกทั่วไป
Route::middleware('auth')->group(function () {
    Route::get('/account', fn () => view('account.home'))->name('account.home');
});

// โซนแอดมิน (ต้องเป็นสมาชิก + is_admin=true)
Route::prefix('admin')->middleware(['auth','admin'])->as('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class)->except(['index','show'])->names('products');
});
