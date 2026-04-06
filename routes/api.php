<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DonationController;

// ── Auth (public) ────────────────────────────────────────────────────────────
Route::post('/register/donor',           [AuthController::class, 'registerDonor']);
Route::post('/register/organization',    [AuthController::class, 'registerOrganization']);
Route::post('/register/partner-kitchen', [AuthController::class, 'registerPartnerKitchen']);
Route::post('/login',                    [AuthController::class, 'login']);
Route::post('/verify/send',              [AuthController::class, 'sendVerificationEmail']);
Route::post('/verify/code',              [AuthController::class, 'verifyCode']);
Route::post('/verify/resend',            [AuthController::class, 'resendCode']);
Route::post('/password/forgot',          [AuthController::class, 'forgotPassword']);
Route::post('/password/verify-code',     [AuthController::class, 'verifyResetCode']);
Route::post('/password/reset',           [AuthController::class, 'resetPassword']);

// ── PayMongo Webhook (public — signed by PayMongo) ────────────────────────────
Route::post('/webhook/paymongo', [DonationController::class, 'paymongoWebhook']);

// ── Protected ────────────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile',   [AuthController::class, 'profile']);
    Route::put('/profile',   [DonationController::class, 'updateProfile']);
    Route::post('/profile/deactivate', [DonationController::class, 'deactivateAccount']);
    Route::post('/logout',   [AuthController::class, 'logout']);
    Route::get('/dashboard', [AuthController::class, 'dashboard']);

    // Donations
    Route::post('/donation/paymongo',   [DonationController::class, 'createPaymongoCheckout']);
    Route::get('/donation/status/{id}', [DonationController::class, 'checkPaymentStatus']);
    Route::post('/donation/food',       [DonationController::class, 'submitFood']);
    Route::post('/donation/schedule',   [DonationController::class, 'submitSchedule']);
    Route::post('/donation/service',    [DonationController::class, 'submitService']);
    Route::get('/donation/stats',       [DonationController::class, 'getDonationStats']);

    // Badges & Activities
    Route::get('/badges',     [DonationController::class, 'getBadges']);
    Route::get('/activities', [DonationController::class, 'getActivities']);
});