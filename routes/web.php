<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - PT Imersa Clothing Catalog
|--------------------------------------------------------------------------
*/

// User / Customer Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserAuthController::class, 'register'])->name('register.post');
});

// User / Customer Logout
Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout')->middleware('auth');

// Frontend Customer Routes (User must login to view catalog & website)
Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
});

// Google OAuth Routes (Login, Linking & Simulator)
Route::prefix('auth/google')->name('auth.google')->group(function () {
    Route::get('/redirect', [GoogleAuthController::class, 'redirect'])->name('');
    Route::get('/callback', [GoogleAuthController::class, 'callback'])->name('.callback');
    Route::post('/unlink', [GoogleAuthController::class, 'unlink'])->name('.unlink')->middleware('auth');
    Route::get('/simulator', [GoogleAuthController::class, 'showSimulator'])->name('.simulator');
    Route::post('/simulator', [GoogleAuthController::class, 'handleSimulator'])->name('.simulator.post');
});

// Admin Panel Routes
Route::redirect('/admin', '/admin/dashboard');
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Admin Auth
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    // Authenticated Admin Only
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Admin Profile & Google Account Settings
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile');
        Route::put('/profile', [AdminProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

        // Admin Resources
        Route::post('products/extract-shopee', [AdminProductController::class, 'extractShopee'])->name('products.extract_shopee');
        Route::resource('products', AdminProductController::class);
        Route::resource('categories', AdminCategoryController::class);
        Route::resource('reviews', AdminReviewController::class);
    });
});
