<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AuthAdmin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminAuthorController;
use App\Http\Controllers\AdminPublisherController;
use App\Http\Controllers\AdminBookController;
use App\Http\Controllers\AdminOrderController;
//Auth::routes();

// Hiển thị giao diện đăng nhập và đăng ký đúng

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [RegisterController::class, 'register'])->name('register');
// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Trang chào mừng
Route::get('/welcome', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
});

Route::middleware(['auth', AuthAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
});

Route::middleware(['auth', AuthAdmin::class])->prefix('admin')->group(function () {
    Route::resource('users', AdminUserController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('authors', AdminAuthorController::class);
    Route::resource('publishers', AdminPublisherController::class);
    Route::resource('books', AdminBookController::class);
    Route::resource('orders', AdminOrderController::class);
});
