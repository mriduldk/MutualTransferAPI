<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\ExpensesController;
use App\Http\Controllers\Api\ExpensesContactController;

use App\Http\Controllers\Api\UserDetailsController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PaymentHistoryController;
use App\Http\Controllers\Api\WalletOfferController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\BlockController;
use App\Http\Controllers\Api\WalletController;



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


Route::controller(UserDetailsController::class)->prefix('userDetails')->group(function () {
    Route::post('savePersonalInformation', 'SaveUserPersonalInformation');
    Route::post('SaveUserEmployeeDetails', 'SaveUserEmployeeDetails');
    Route::post('SaveUserSchoolDetails', 'SaveUserSchoolDetails');
    Route::post('SaveUserPreferredDistrict', 'SaveUserPreferredDistrict');
    Route::post('ChangeActivelyLookingStatus', 'ChangeActivelyLookingStatus');
    Route::post('UseReferralCode', 'UseReferralCode');
});

Route::controller(SearchController::class)->prefix('search')->group(function () {
    Route::post('SearchPerson', 'SearchPerson');
    Route::post('ViewPersonDetails', 'ViewPersonDetails');
});


Route::controller(PaymentController::class)->prefix('payment')->group(function () {
    Route::post('SaveUserPayForAnotherUser', 'SaveUserPayForAnotherUser');
});


Route::controller(PaymentHistoryController::class)->prefix('paymentHistory')->group(function () {
    Route::post('SavePaymentHistory', 'SavePaymentHistory');
});

Route::controller(WalletOfferController::class)->prefix('walletOffer')->group(function () {
    Route::post('GetWalletOffers', 'GetWalletOffers');
});

Route::controller(WalletController::class)->prefix('wallet')->group(function () {
    Route::post('GetWalletDataByUser', 'GetWalletDataByUser');
});

Route::controller(DistrictController::class)->prefix('district')->group(function () {
    Route::post('GetAllDistricts', 'GetAllDistricts');
    Route::post('GetDistrictByName', 'GetDistrictByName');
    Route::post('GetDistrictsByStateAndDistrictName', 'GetDistrictsByStateAndDistrictName');
    Route::post('GetDistrictByState', 'GetDistrictByState');
});


Route::controller(BlockController::class)->prefix('block')->group(function () {
    Route::post('GetAllBlocks', 'GetAllBlocks');
    Route::post('GetBlocksByDistrict', 'GetBlocksByDistrict');
    Route::post('GetBlocksByDistrictAndBlockName', 'GetBlocksByDistrictAndBlockName');
    Route::post('GetBlocksByState', 'GetBlocksByState');
});









Route::controller(GroupController::class)->prefix('group')->group(function () {
    Route::post('create', 'createGroup');
    Route::post('getAllByUserID', 'getAllByUserID');
});

Route::controller(ExpensesController::class)->prefix('expenses')->group(function () {
    Route::post('create', 'createExpenses');
    Route::post('getAllByGroupID', 'getAllByGroupID');
    Route::post('getAllByGroupIDWithContactDetails', 'getAllByGroupIDWithContactDetails');
    Route::post('getAllByUserIDWithContactDetails', 'getAllByUserIDWithContactDetails');
    Route::post('getAllExpensesByMonth', 'getAllExpensesByMonth');
    Route::post('getExpensesByID', 'getExpensesByID');
});

Route::controller(ExpensesContactController::class)->prefix('expensesContact')->group(function () {
    Route::post('create', 'createExpensesContact');
    Route::post('getAllByExpensesID', 'getAllByExpensesID');
});



