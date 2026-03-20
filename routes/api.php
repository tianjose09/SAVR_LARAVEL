<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/register/donor', [AuthController::class, 'registerDonor']);
Route::post('/register/organization', [AuthController::class, 'registerOrganization']);
Route::post('/register/partner-kitchen', [AuthController::class, 'registerPartnerKitchen']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});