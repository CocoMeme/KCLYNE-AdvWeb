<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CustomerController;
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

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/create_product', [ProductController::class, 'store']);
    Route::get('/get_product/{id}', [ProductController::class, 'get_product']);
    Route::get('/get_all_products', [ProductController::class, 'get_all_products']);
    Route::put('/update_product/{id}', [ProductController::class, 'update']);
    Route::patch('/product/status/{id}', [ProductController::class, 'updateStatus']);
    Route::delete('/product/delete/{id}', [ProductController::class, 'destroy'])->name('product.delete');
});

/*
|--------------------------------------------------------------------------
| CART/SHOP/ORDER
|--------------------------------------------------------------------------
*/

Route::get('/cart', [CartController::class, 'getCart']);
Route::get('/products', [ShopController::class, 'getProducts']);
Route::get('/services', [ShopController::class, 'getServices']);
Route::get('/services/fetch', [ServiceController::class, 'getcustomer_service_index'])->name('services.getcustomer_service_index');

Route::middleware('auth:sanctum')->group(function () {

    Route::middleware(['auth', 'check.status'])->group(function () {

        Route::get('/customer', [AuthController::class, 'getCustomerInfo']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::post('/cart/add', [CartController::class, 'addToCart']);
        Route::post('/cart/update', [CartController::class, 'updateQuantity']);
        Route::delete('/cart/{product}', [CartController::class, 'delete']);
        Route::delete('/cart/delete-selected', [CartController::class, 'deleteSelected']);

    });
});

/*
|--------------------------------------------------------------------------
| REVIEW
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/submit-review/{type}', [ReviewController::class, 'submitReview']);
});

Route::get('/comments/{serviceId}', [ReviewController::class, 'fetchComments']);
Route::get('/products/{id}/reviews', [ShopController::class, 'getProductReviews']);
Route::get('/review-details/{type}/{id}', [ReviewController::class, 'getReviewDetails']);

/*
|--------------------------------------------------------------------------
| ORDER HISTORY
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/order-details', [OrderController::class, 'getOrderDetails']);
});

/*
|--------------------------------------------------------------------------
| EMPLOYEE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
Route::post('/create_employee', [EmployeeController::class, 'store']);
Route::get('/get_employee/{id}', [EmployeeController::class, 'get_employee']);
Route::get('/get_all_employees', [EmployeeController::class, 'get_all_employees']);
Route::put('/update_employee/{id}', [EmployeeController::class, 'update']);
Route::patch('/employee/status/{id}', [EmployeeController::class, 'updateStatus']);
Route::delete('/delete_employee/{id}', [EmployeeController::class, 'destroy'])->name('employee.delete');
});

/*
|--------------------------------------------------------------------------
| SERVICE
|--------------------------------------------------------------------------
*/

Route::get('/get_all_services', [ServiceController::class, 'get_all_service']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
Route::post('/apicreate', [ServiceController::class, 'apistore'])->name('api.store');
Route::get('/get_service/{id}', [ServiceController::class, 'get_service']);
Route::put('/apiupdate/{id}', [ServiceController::class, 'apiupdate']);
Route::delete('/apidelete/{id}', [ServiceController::class, 'apidelete']);
});


/*
|--------------------------------------------------------------------------
| CUSTOMER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
Route::patch('customer/status/{id}', [CustomerController::class, 'updateStatus']);
});