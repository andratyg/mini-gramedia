<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookCategoryController;
use App\Http\Controllers\SubscriptionPackageController;
use App\Models\SubscriptionPackage;
use App\http\Controllers\BookController;


Route::get('/', function () {
    $subscriptionPackages = SubscriptionPackage::all();
    return view('home', compact('subscriptionPackages'));
})->name('home');

//kelompok route yang hanya bisa diakses oleh user yang sudah login
Route::middleware(['isLoggedIn'])->group(function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    //prefix untuk mengelompokkan route admin yang path nya diawali dengan /admin
    //seluruh route pada kelompok ini akan memiliki nama route diawali dengan admin. contoh : admin.dashboard
    Route::prefix('admin')->name('admin.')->middleware('isAdmin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('book-categories', BookCategoryController::class);

        Route::resource('subscription-package', SubscriptionPackageController::class);
        Route::resource('books', BookController::class);
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
