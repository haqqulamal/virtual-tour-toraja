<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VirtualTourController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\SceneController as AdminSceneController;
use App\Http\Controllers\Admin\HotspotController as AdminHotspotController;
use App\Http\Controllers\Admin\ArtifactController as AdminArtifactController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Virtual Tour Budaya Toraja - Kecamatan Baruppu', North Toraja
| Interactive 360° Virtual Tour with Pannellum.js
|
*/

// ================================================================================
// PUBLIC FRONTEND ROUTES
// ================================================================================

// Virtual Tour Routes
Route::get('/', [VirtualTourController::class, 'index'])
    ->name('tour.index');

Route::get('/tour/{scene}', [VirtualTourController::class, 'show'])
    ->name('tour.show')
    ->where('scene', '[0-9]+');

Route::get('/tour/data/{scene}', [VirtualTourController::class, 'getSceneData'])
    ->name('tour.data')
    ->where('scene', '[0-9]+');

// Collection (Artifacts) Routes
Route::get('/collection', [CollectionController::class, 'index'])
    ->name('collection.index');

Route::get('/collection/{artifact}', [CollectionController::class, 'show'])
    ->name('collection.show')
    ->where('artifact', '[0-9]+');

// Locale/Language Routes
Route::get('/language/{locale}', [LocaleController::class, 'switchLocale'])
    ->name('locale.switch')
    ->where('locale', 'id|en');

// ================================================================================
// AUTHENTICATION ROUTES (Laravel Breeze)
// ================================================================================

Auth::routes([
    'register' => false, // Disable registration - admin only
]);

// ================================================================================
// ADMIN ROUTES (Protected - Requires Authentication + Admin Role)
// ================================================================================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    // Scene Management (Full CRUD)
    Route::resource('scenes', AdminSceneController::class, [
        'except' => ['show'],
    ]);
    Route::post('scenes/reorder', [AdminSceneController::class, 'reorder'])
        ->name('scenes.reorder');

    // Hotspot Management (Full CRUD)
    Route::resource('hotspots', AdminHotspotController::class, [
        'except' => ['show'],
    ]);
    Route::get('hotspots/scene/{scene}', [AdminHotspotController::class, 'byScene'])
        ->name('hotspots.by-scene')
        ->where('scene', '[0-9]+');

    // Artifact Management (Full CRUD)
    Route::resource('artifacts', AdminArtifactController::class, [
        'except' => ['show'],
    ]);

    // Category Management (Full CRUD)
    Route::resource('categories', AdminCategoryController::class, [
        'except' => ['show'],
    ]);
});

// ================================================================================
// FALLBACK 404
// ================================================================================

Route::fallback(function () {
    return view('errors.404');
});
