<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\ExpensesController;

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



Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('checkUserPhoneNumber', 'checkUserPhoneNumber');
    Route::post('otpVerify', 'otpVerify');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::controller(GroupController::class)->prefix('group')->group(function () {
    Route::post('create', 'createGroup');
    Route::post('getAllByUserID', 'getAllByUserID');
});

Route::controller(ExpensesController::class)->prefix('expenses')->group(function () {
    Route::post('create', 'createExpenses');
    Route::post('getAllByGroupID', 'getAllByGroupID');
});



