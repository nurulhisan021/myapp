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
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Account\AddressController;

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
    Route::post('/account/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('account.orders.cancel');

    // Checkout
    Route::post('/buy-now', [CheckoutController::class, 'initiateBuyNow'])->name('buy-now.submit');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Address Book
    Route::resource('account/addresses', AddressController::class)->except(['show'])->names('account.addresses');

    // Profile Management
    Route::get('/account/profile', [\App\Http\Controllers\Account\ProfileController::class, 'edit'])->name('account.profile.edit');
    Route::put('/account/profile', [\App\Http\Controllers\Account\ProfileController::class, 'update'])->name('account.profile.update');

    // Wishlist
    Route::get('/account/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

// โซนแอดมิน (ต้องเป็นสมาชิก + is_admin=true)
Route::prefix('admin')->middleware(['auth','admin'])->as('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class)->except(['show'])->names('products');
    Route::resource('categories', CategoryController::class)->except(['show'])->names('categories');
    Route::resource('admins', AdminController::class)->names('admins');

    // Bank Account Settings
    Route::get('bank-account', [BankAccountController::class, 'index'])->name('bank-account.index');
    Route::put('bank-account', [BankAccountController::class, 'update'])->name('bank-account.update');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
});
