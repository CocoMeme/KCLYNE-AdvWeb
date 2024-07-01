<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*
|--------------------------------------------------------------------------
| GENERAL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('customer.home');
});

Route::get('/home', function () {
    return view('customer.home');
})->name('home');

/*
|--------------------------------------------------------------------------
| ACCOUNTS
|--------------------------------------------------------------------------
*/

Route::get('/register', [AccountController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AccountController::class, 'register']);

Route::get('/login', [AccountController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AccountController::class, 'login'])->name('login');
Route::post('/logout', [AccountController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMINS
|--------------------------------------------------------------------------
*/

// Ensure these routes are only accessible to authenticated users with 'admin' role
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/{id}/update-role', [AdminController::class, 'updateRole']);
    Route::post('/admin/users/{id}/deactivate', [AdminController::class, 'deactivate']);
    Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products');
});

/*
|--------------------------------------------------------------------------
| PRODUCTS
|--------------------------------------------------------------------------
*/

// Other product routes can be added here as needed
