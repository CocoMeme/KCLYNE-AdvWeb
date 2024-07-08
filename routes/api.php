<?php

use App\Http\Controllers\EmployeeController;
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

//EMPLOYEE
Route::post('/create_employee', [EmployeeController::class, 'store']);

Route::get('/get_employee/{id}', [EmployeeController::class, 'get_employee']);
Route::get("/get_all_employee", [EmployeeController::class, 'get_all_employee']);

Route::put('/update_employee/{id}', [EmployeeController::class, 'update']);
Route::patch('/employee/status/{id}', [EmployeeController::class, 'updateStatus']);

Route::delete('/employee/delete/{id}', [EmployeeController::class, 'destroy'])->name('employee.delete');
