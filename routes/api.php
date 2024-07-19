<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| PRODUCTS
|--------------------------------------------------------------------------
*/

Route::post('/create_product', [ProductController::class, 'store']);
Route::get('/get_product/{id}', [ProductController::class, 'get_product']);
Route::get('/get_all_products', [ProductController::class, 'get_all_products']);
Route::put('/update_product/{id}', [ProductController::class, 'update']);
Route::patch('/product/status/{id}', [ProductController::class, 'updateStatus']);
Route::delete('/product/delete/{id}', [ProductController::class, 'destroy'])->name('product.delete');

/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::get('/customer', [AuthController::class, 'getCustomerInfo']);
    Route::get('/products', [ShopController::class, 'getProducts']);
    Route::get('/services', [ShopController::class, 'getServices']);
    Route::post('/cart/update', [CartController::class, 'updateQuantity']);
    Route::delete('/cart/{product}', [CartController::class, 'delete']);
});

/*
|--------------------------------------------------------------------------
| EMPLOYEE
|--------------------------------------------------------------------------
*/

Route::post('/create_employee', [EmployeeController::class, 'store']);
Route::get('/get_employee/{id}', [EmployeeController::class, 'get_employee']);
Route::get('/get_all_employees', [EmployeeController::class, 'get_all_employees']);
Route::put('/update_employee/{id}', [EmployeeController::class, 'update']);
Route::patch('/employee/status/{id}', [EmployeeController::class, 'updateStatus']);
Route::delete('/employee/delete/{id}', [EmployeeController::class, 'destroy'])->name('employee.delete');

/*
|--------------------------------------------------------------------------
| SERVICE
|--------------------------------------------------------------------------
*/

Route::post('/apicreate', [ServiceController::class, 'apistore'])->name('api.store');
Route::get('/get_service/{id}', [ServiceController::class, 'get_service']);
Route::get('/get_all_service', [ServiceController::class, 'get_all_service']);
Route::put('/apiupdate/{id}', [ServiceController::class, 'apiupdate']);
Route::delete('/apidelete/{id}', [ServiceController::class, 'apidelete']);
