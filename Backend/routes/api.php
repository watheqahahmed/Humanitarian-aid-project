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
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// مسارات التسجيل وتسجيل الدخول
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// مسار التبرع العام بدون تسجيل دخول
Route::post('/donations', [DonationController::class, 'storePublic']);

// مسارات محمية
Route::middleware('auth:sanctum')->group(function () {

    // تسجيل الخروج
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    // بيانات المستخدم
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // مسارات المسؤول (Admin)
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // إدارة التبرعات
        Route::apiResource('donations', DonationController::class);
        Route::put('donations/{donation}/status', [DonationController::class, 'updateStatus']); // مسار تحديث الحالة

        // إدارة طلبات المساعدة
        Route::apiResource('aid-requests', AdminAidRequestController::class)->except(['store']);
        Route::apiResource('distributions', AdminDistributionController::class);

        // لوحة التحكم
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // تصدير البيانات
        Route::get('/reports/donations', [ReportController::class, 'exportDonations']);
        Route::get('/reports/aid-requests', [ReportController::class, 'exportAidRequests']);

        // إدارة المستفيدين والمتطوعين
        Route::get('/beneficiaries', [BeneficiaryController::class, 'index']);
        Route::get('/volunteers', [UserController::class, 'volunteers']);

        // إدارة المستخدمين
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });

    // مسارات المستفيد (Beneficiary)
    Route::middleware('role:beneficiary')->prefix('beneficiary')->group(function () {
        Route::apiResource('aid-requests', BeneficiaryAidRequestController::class)
            ->only(['index', 'store', 'show']);
        Route::get('/aid-requests/{aidRequest}/distribution', [BeneficiaryController::class, 'showDistributionDetails']);
    });

    // مسارات المتطوع (Volunteer)
    Route::middleware('role:volunteer')->prefix('volunteer')->group(function () {
        Route::get('/deliveries', [DeliveryController::class, 'index']);
        Route::put('/deliveries/{distribution}', [DeliveryController::class, 'update']);
    });

    // مسارات الإشعارات (محمي)
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('/{notification}/mark-as-read', [NotificationController::class, 'markAsRead']);
    });

    // تغيير كلمة المرور
    Route::post('/user/change-password', [UserController::class, 'changePassword']);
});
