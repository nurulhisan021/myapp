<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\BankAccountController;

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
    // Order History
    Route::get('/account', [OrderController::class, 'index'])->name('account.orders.index');
    Route::get('/account/orders/{order}', [OrderController::class, 'show'])->name('account.orders.show');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
});

// โซนแอดมิน (ต้องเป็นสมาชิก + is_admin=true)
Route::prefix('admin')->middleware(['auth','admin'])->as('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class)->except(['show'])->names('products');
    Route::resource('categories', CategoryController::class)->except(['show'])->names('categories');

    // Bank Account Settings
    Route::get('bank-account', [BankAccountController::class, 'index'])->name('bank-account.index');
    Route::put('bank-account', [BankAccountController::class, 'update'])->name('bank-account.update');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
});
