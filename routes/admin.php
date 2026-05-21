<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AdminDashboardController,
    DashboardController,
    AdminDatasetController,
    AdminUserController,
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

// Admin Routes
Route::middleware(['auth', 'role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Datasets CRUD
    Route::resource('datasets', AdminDatasetController::class)->except(['show']);
    Route::post('datasets/{dataset}/approve', [AdminDatasetController::class, 'approve'])->name('datasets.approve');
    Route::post('datasets/{dataset}/reject', [AdminDatasetController::class, 'reject'])->name('datasets.reject');
    Route::post('datasets/bulk-action', [AdminDatasetController::class, 'bulkAction'])->name('datasets.bulk-action');
    Route::get('datasets/export', [AdminDatasetController::class, 'export'])->name('datasets.export');
    
    // Users CRUD
    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::post('users/{user}/toggle-ban', [AdminUserController::class, 'toggleBan'])->name('users.toggle-ban');
    Route::post('users/bulk-action', [AdminUserController::class, 'bulkAction'])->name('users.bulk-action');
    Route::get('users/export', [AdminUserController::class, 'export'])->name('users.export');

    // Admin Statistics Route
Route::get('/statistics', function() {
    // Statistics data
    $stats = [
        'total_datasets' => \App\Models\Dataset::count(),
        'total_users' => \App\Models\User::count(),
        'total_papers' => \App\Models\Paper::count(),
        'pending_datasets' => \App\Models\Dataset::where('status', 'pending')->count(),
        'approved_datasets' => \App\Models\Dataset::where('status', 'approved')->count(),
    ];
    
    return view('admin.statistics', compact('stats'));
})->name('admin.statistics');
// Statistics Route
Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
});