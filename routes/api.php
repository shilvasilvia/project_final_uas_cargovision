<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CountryApiController;
use App\Http\Controllers\Api\PortApiController;
use App\Http\Controllers\Api\ShipmentApiController;
use App\Http\Controllers\Api\WeatherAlertApiController;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\RiskScoreApiController;

/*
|--------------------------------------------------------------------------
| REST API Routes - Global Supply Chain Risk Intelligence
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/health', function () {
    return response()->json([
        'status' => 'online',
        'message' => 'REST API Global Supply Chain Risk Intelligence Active',
        'version' => '1.0.0'
    ]);
});

Route::post('/login', [AuthApiController::class, 'login']);

// Protected Routes (Sanctum Authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/user', [AuthApiController::class, 'user']);

    // API Resources
    Route::apiResource('countries', CountryApiController::class);
    Route::apiResource('ports', PortApiController::class);
    Route::apiResource('shipments', ShipmentApiController::class);
    Route::apiResource('weather-alerts', WeatherAlertApiController::class);
    Route::apiResource('news', NewsApiController::class);
    Route::apiResource('risk-scores', RiskScoreApiController::class)->except(['store', 'update', 'destroy']);

    // Extra Calculation Trigger
    Route::post('risk-scores/calculate', [RiskScoreApiController::class, 'calculate']);
});