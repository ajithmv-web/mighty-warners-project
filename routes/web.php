<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImportController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/products/{product}', [ShopController::class, 'show'])->name('shop.show');

// Cart 
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{productId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::delete('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
Route::get('/cart/checkout', [CartController::class, 'showCheckout'])->name('cart.checkout.form');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // User 
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/orders', [\App\Http\Controllers\User\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [\App\Http\Controllers\User\OrderController::class, 'show'])->name('orders.show');
    });
    
    // Admin 
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::resource('products', ProductController::class)->except(['show']);
        Route::get('products/import', [ProductImportController::class, 'showImportForm'])->name('products.import');
        Route::post('products/import', [ProductImportController::class, 'import'])->name('products.import.process');
        Route::get('products/export/sample', [ProductImportController::class, 'exportSample'])->name('products.export.sample');
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('colors', ColorController::class)->except(['show']);
        Route::resource('sizes', SizeController::class)->except(['show']);
        Route::resource('coupons', CouponController::class)->except(['show']);
        Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });
});

require __DIR__.'/auth.php';
