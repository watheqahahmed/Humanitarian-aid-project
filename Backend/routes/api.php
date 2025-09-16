<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Volunteer\DeliveryController;
use App\Http\Controllers\Beneficiary\AidRequestController as BeneficiaryAidRequestController;
use App\Http\Controllers\Admin\AidRequestController;
use App\Http\Controllers\Admin\DonationController;
use App\Http\Controllers\AuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// مسار تسجيل الدخول (login)
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::apiResource('donations', DonationController::class);
Route::apiResource('aid-requests', AidRequestController::class)->except(['store']);
Route::apiResource('beneficiary/aid-requests', BeneficiaryAidRequestController::class)->only(['index', 'store', 'show']);
Route::prefix('volunteer')->group(function () {
    Route::get('/deliveries', [DeliveryController::class, 'index']);
    Route::post('/deliveries/{distribution}', [DeliveryController::class, 'update']);
});
