<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RouteController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('/dashboard', DashboardController::class);
Route::resource('/pakets', PaketController::class);
Route::resource('/deliveries', DeliveryController::class);

Route::get('/deliveries/{kodePengiriman}/route', [DeliveryController::class, 'getOptimizedRoute'])->name('deliveries.route');
Route::get('/optimized-route', [DeliveryController::class, 'getOptimizedRoute'])->name('deliveries.optimize');


Route::post('/store-route-summary-polyline', [RouteController::class, 'storeSummaryPolyline']);
Route::get('/route-summary/{kode_pengiriman}', [RouteController::class, 'getRouteSummary']);
Route::get('/deliveries/route-data/{kodePengiriman}', [RouteController::class, 'getRouteData']);


// Route::get('/pakets', function () {
//     return view('pakets.index');
// })->middleware(['auth', 'verified'])->name('pakets');

// Route::get('/deliveries', function () {
//     return view('deliveries.index');
// })->middleware(['auth', 'verified'])->name('deliveries');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
