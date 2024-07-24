<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;

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

Route::middleware('auth:sanctum')->group(function () {
Route::get('/thank-you', function () {
    return view('shop.ty');
});
});

/*
|--------------------------------------------------------------------------
| ACCOUNTS
|--------------------------------------------------------------------------
*/

Route::get('/register', [AccountController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AccountController::class, 'register']);

Route::get('/login', [AccountController::class, 'showLoginForm'])->name('showlogin');
Route::post('/login', [AccountController::class, 'login'])->name('login');
Route::post('/logout', [AccountController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMINS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/{id}/update-role', [AdminController::class, 'updateRole']);
    Route::post('/admin/users/{id}/deactivate', [AdminController::class, 'deactivate']);
    Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {
Route::get('/admin/dashboard', [DashboardController::class, 'index']);
});

/*
|--------------------------------------------------------------------------
| PRODUCTS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {
Route::get('/product', [ProductController::class, 'index'])->name('product.index');
Route::resource('product', ProductController::class);
Route::post('/product/import', [ProductController::class, 'import'])->name('product.import');
Route::post('/product/export', [ProductController::class, 'export'])->name('product.export');
});

/*
|--------------------------------------------------------------------------
| EMPLOYEES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {
Route::get('/employee', [EmployeeController::class, 'index'])->name('employee.index');
Route::resource('employee', EmployeeController::class);
Route::post('/employee/import', [EmployeeController::class, 'import'])->name('employee.import');
Route::post('/employee/export', [EmployeeController::class, 'export'])->name('employee.export');
});


/*
|--------------------------------------------------------------------------
| SHOP
|--------------------------------------------------------------------------
*/

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/{id}', [ShopController::class, 'show'])->name('shop.show');

/*
|--------------------------------------------------------------------------
| ORDER HISTORY
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {
Route::get('/orders/history', [OrderController::class, 'myOrders']);
});

/*
|--------------------------------------------------------------------------
| SERVICE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {
Route::get('/service', [ServiceController::class, 'index']);
Route::post('/store', [ServiceController::class, 'store'])->name('store');
Route::get('/fetchall', [ServiceController::class, 'fetchAll'])->name('fetchAll');
Route::delete('/delete', [ServiceController::class, 'delete'])->name('delete');
Route::get('/edit', [ServiceController::class, 'edit'])->name('edit');
Route::post('/update', [ServiceController::class, 'update'])->name('update');

Route::get('/export', [ServiceController::class, 'export'])->name('export');
Route::post('/import', [ServiceController::class, 'import'])->name('import');
});

Route::get('/services', [ServiceController::class, 'customer_service_index']);
Route::get('/getcustomer_service_index', [ServiceController::class, 'getcustomer_service_index'])->name('services.getcustomer_service_index');