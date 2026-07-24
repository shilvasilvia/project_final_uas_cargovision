<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\WeatherAlertController;
use App\Http\Controllers\RiskScoreController;
use App\Http\Controllers\EconomicDataController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CountryComparisonController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FavoriteController;
use App\Services\WorldBankService;
use App\Services\CurrencyService;

/*
|--------------------------------------------------------------------------
| Web Routes - RBAC Controlled (Admin vs User)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard (Semua User)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Read-Only & Favorites Routes (Akses Seluruh User Logged-In)
Route::middleware('auth')->group(function () {
    // Read-Only Views
    Route::resource('countries', CountryController::class)->only(['index', 'show']);
    Route::resource('ports', PortController::class)->only(['index', 'show']);
    Route::resource('shipments', ShipmentController::class)->only(['index', 'show']);
    Route::resource('weather-alerts', WeatherAlertController::class)->only(['index', 'show']);
    Route::resource('risk-scores', RiskScoreController::class)->only(['index', 'show']);
    Route::resource('economic-data', EconomicDataController::class)->only(['index']);
    Route::resource('news', NewsController::class)->only(['index', 'show']);
    Route::resource('country-comparisons', CountryComparisonController::class)->only(['index']);

    // Favorite Monitoring (Khusus User & Admin)
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin-Only Routes (Full CRUD Write Operations & Reports/Export)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('countries', CountryController::class)->except(['index', 'show']);
    Route::resource('ports', PortController::class)->except(['index', 'show']);
    Route::resource('shipments', ShipmentController::class)->except(['index', 'show']);
    Route::resource('weather-alerts', WeatherAlertController::class)->except(['index', 'show']);
    Route::post('risk-scores/calculate', [RiskScoreController::class, 'calculate'])->name('risk-scores.calculate');
    Route::resource('economic-data', EconomicDataController::class)->except(['index']);
    Route::resource('news', NewsController::class)->except(['index', 'show']);

    // Reports & Export (Khusus Admin)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
    Route::get('/reports/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');
});

// API Testing routes
Route::get('/test-worldbank', function (WorldBankService $worldBank) {
    return response()->json([
        'gdp' => $worldBank->getGDP('id'),
        'inflation' => $worldBank->getInflation('id'),
    ]);
});

require __DIR__.'/auth.php';
