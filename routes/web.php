<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookCategoryController;
use App\Http\Controllers\SubscriptionPackageController;
use App\http\Controllers\BookController;
use App\Http\Controllers\SubscriptionPackageBookController;

// Route halaman utama (Home) yang menampilkan paket langganan dan buku
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route untuk pencarian global navbar
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Route untuk membuka halaman daftar buku berdasarkan kategori yang dipilih
Route::get('/category/{id}', [HomeController::class, 'category'])->name('category.books');

// Route untuk membuka halaman detail paket langganan dan daftar buku yang tertaut (Tugas 6.2)
Route::get('/subscription-package/{id}', [HomeController::class, 'subscriptionPackageDetail'])->name('subscription-package.detail');

//kelompok route yang hanya bisa diakses oleh user yang sudah login
Route::middleware(['isLoggedIn'])->group(function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('subscription-package/{id}/checkout', [SubscriptionPackageController::class, 'checkoutPage'])->name('subscription-package.checkout.page');
    Route::post('subscription-package/{id}/checkout', [SubscriptionPackageController::class, 'checkout'])->name('subscription-package.checkout');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.show');
    //prefix untuk mengelompokkan route admin yang path nya diawali dengan /admin
    //seluruh route pada kelompok ini akan memiliki nama route diawali dengan admin. contoh : admin.dashboard
    Route::prefix('admin')->name('admin.')->middleware('isAdmin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('book-categories', BookCategoryController::class);
        Route::resource('books', BookController::class);
        Route::get('subscription-package/{id}/books', [SubscriptionPackageBookController::class, 'show'])->name('subscription-package.books');
        Route::post('subscription-package/{id}/books', [SubscriptionPackageBookController::class, 'store'])->name('subscription-package.books.store');
        Route::resource('subscription-package', SubscriptionPackageController::class);

    });
});
//kelompok route yang hanya bisa diakses oleh user yang belum login
Route::middleware(['isGuest'])->group(function () {
    Route::get('/register', function () {
        return view('register');
    })->name('register');

    Route::post('/register', [UserController::class, 'register'])->name('register.store')->middleware('throttle:5,1');

    Route::get('/login', function () {
        return view('login');
    })->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('login.store')->middleware('throttle:5,1');
});
