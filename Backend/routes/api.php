<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DonationController;
use App\Http\Controllers\Admin\AidRequestController as AdminAidRequestController;
use App\Http\Controllers\Beneficiary\AidRequestController as BeneficiaryAidRequestController;
use App\Http\Controllers\Volunteer\DeliveryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\DistributionController as AdminDistributionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Beneficiary\BeneficiaryController;


Route::post('/login', [AuthController::class, 'login']);

// مسارات محمية
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // مسارات خاصة بالمسؤول (Admin)
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::apiResource('donations', DonationController::class);
        Route::apiResource('aid-requests', AdminAidRequestController::class)->except(['store']);
    });

    // مسارات خاصة بالمستفيد (Beneficiary)
    Route::middleware('role:beneficiary')->prefix('beneficiary')->group(function () {
        Route::apiResource('aid-requests', BeneficiaryAidRequestController::class)->only(['index', 'store', 'show']);
    });

    // مسارات خاصة بالمتطوع (Volunteer)
    Route::middleware('role:volunteer')->prefix('volunteer')->group(function () {
        Route::get('/deliveries', [DeliveryController::class, 'index']);
        Route::post('/deliveries/{distribution}', [DeliveryController::class, 'update']);
    });
});



Route::prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::post('/{notification}/mark-as-read', [NotificationController::class, 'markAsRead']);
});

Route::middleware('role:admin')->prefix('admin')->group(function () {
    // ...
    Route::apiResource('distributions', AdminDistributionController::class);
});

Route::middleware('role:admin')->prefix('admin')->group(function () {
    // ...
    Route::get('/dashboard', [DashboardController::class, 'index']);
});


Route::middleware('role:admin')->prefix('admin')->group(function () {
    // ...
    Route::get('/reports/donations', [ReportController::class, 'exportDonations']);
});

Route::middleware('role:admin')->prefix('admin')->group(function () {
    // ...
    Route::get('/reports/donations', [ReportController::class, 'exportDonations']);
    Route::get('/reports/aid-requests', [ReportController::class, 'exportAidRequests']);
});


// مسارات خاصة بالمستفيد (Beneficiary)
Route::middleware('role:beneficiary')->prefix('beneficiary')->group(function () {
    // ...
    Route::get('/aid-requests/{aidRequest}/distribution', [BeneficiaryController::class, 'showDistributionDetails']);
});
