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
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;
//Auth::routes();

// Hiển thị giao diện đăng nhập và đăng ký đúng

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [RegisterController::class, 'register']);
// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home.index');
// Trang hiển thị tất cả sản phẩm với bộ lọc
Route::get('/products', [HomeController::class, 'allProducts'])->name('products.all');
// Trang hiển thị sách theo danh mục
Route::get('/category/{id}', [HomeController::class, 'category'])->name('category.show');
// Trang chi tiết sách
Route::get('/book/{id}', [HomeController::class, 'bookDetail'])->name('book.detail');

// Giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{bookId}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{itemId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{itemId}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Thanh toán
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{orderId}', [CheckoutController::class, 'success'])->name('checkout.success');

// Đánh giá sản phẩm
Route::post('/book/{bookId}/review', [ReviewController::class, 'store'])->name('review.store');
Route::get('/book/{bookId}/reviews', [ReviewController::class, 'getReviews'])->name('review.get');
Route::get('/book/{bookId}/can-review', [ReviewController::class, 'canReview'])->name('review.canReview');

// Test route để debug
Route::get('/test-category', function () {
    return 'Test route works! This is NOT the index page.';
});

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
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/count', [NotificationController::class, 'getUnreadCount'])->name('notifications.count');
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/clear-read', [NotificationController::class, 'clearRead'])->name('notifications.clearRead');
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
