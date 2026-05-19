<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DashboardController,
    DatasetReviewController,
    UserManagementController,
    StatisticsController
};

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "admin" middleware group. Make something great!
|
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Dataset Review
    Route::prefix('datasets')->name('datasets.')->group(function () {
        Route::get('/', [DatasetReviewController::class, 'index'])->name('index');
        Route::get('/{dataset}', [DatasetReviewController::class, 'show'])->name('show');
        Route::get('/{dataset}/review', [DatasetReviewController::class, 'show'])->name('review');
        Route::post('/{dataset}/approve', [DatasetReviewController::class, 'approve'])->name('approve');
        Route::post('/{dataset}/reject', [DatasetReviewController::class, 'reject'])->name('reject');
        Route::post('/{dataset}/pending', [DatasetReviewController::class, 'setPending'])->name('pending');
        Route::post('/bulk-approve', [DatasetReviewController::class, 'bulkApprove'])->name('bulk-approve');
    });
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
        Route::put('/{user}/role', [UserManagementController::class, 'updateRole'])->name('update-role');
        Route::post('/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    // Statistics
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
    
    // Settings (optional)
    Route::get('/settings', function() {
        return view('admin.settings');
    })->name('settings');
});