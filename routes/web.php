<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    DatasetController,
    ProfileController,
    ContributeController,
    SocialAuthController,
    PaperController
};
use App\Http\Controllers\Admin\{
    AdminDashboardController,
    AdminDatasetController,
    AdminUserController,
    AdminKeywordController,
    StatisticsController,
    DatasetReviewController,
    UserManagementController
};
use App\Http\Middleware\RoleMiddleware;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===== 🌐 PUBLIC ROUTES (No Auth Required) =====

// Home & About
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('about')->name('about.')->group(function () {
    Route::get('/', [HomeController::class, 'about'])->name('index');
    Route::get('/who-we-are', [HomeController::class, 'whoWeAre'])->name('who-we-are');
    Route::get('/citation', [HomeController::class, 'citation'])->name('citation');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
});

// Datasets (Public)
Route::prefix('datasets')->name('datasets.')->group(function () {
    Route::get('/', [DatasetController::class, 'index'])->name('index');
    Route::get('/{dataset}', [DatasetController::class, 'show'])->name('show');
    Route::get('/{dataset}/files/{file}/download', [DatasetController::class, 'download'])
        ->name('download')
        ->middleware('throttle:30,1');
    
    // Dataset Interactions
    Route::post('/{dataset}/track-view', [DatasetController::class, 'trackView'])
        ->name('track-view')
        ->middleware('throttle:60,1');
    
    Route::post('/{dataset}/save', [DatasetController::class, 'save'])
        ->name('save')
        ->middleware(['auth', 'throttle:30,1']);
    
    Route::get('/{dataset}/preview', [DatasetController::class, 'preview'])->name('preview');
});

// Search
Route::get('/search', function (\Illuminate\Http\Request $request) {
    return redirect()->route('datasets.index', $request->only('q', 'task', 'area', 'instances'));
})->name('search');

// Contribute Policy
Route::get('/contribute', [ContributeController::class, 'policy'])->name('contribute.policy');

// Route untuk Social Login
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('google.callback');

Route::get('/auth/github', [SocialAuthController::class, 'redirectToGithub'])->name('github.login');
Route::get('/auth/github/callback', [SocialAuthController::class, 'handleGithubCallback'])->name('github.callback');


// ===== 🔐 AUTHENTICATED ROUTES =====
Route::middleware('auth')->group(function () {
    
    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::get('/datasets', [ProfileController::class, 'datasets'])->name('datasets');
        Route::get('/dataset/{dataset}', [ProfileController::class, 'showDataset'])->name('dataset.show');
        Route::put('/dataset/{dataset}/status', [ProfileController::class, 'updateDatasetStatus'])->name('dataset.update-status');
        Route::put('/dataset/{dataset}/visibility', [ProfileController::class, 'updateVisibility'])->name('dataset.update-visibility');
        Route::get('/edits', [ProfileController::class, 'edits'])->name('edits');
    });
    
    // Contribute: Donation Flow
    Route::prefix('contribute/donation')->name('contribute.')->group(function () {
        Route::get('/metadata', [ContributeController::class, 'createMetadata'])->name('metadata');
        Route::post('/metadata', [ContributeController::class, 'storeMetadata'])->name('metadata.store');
        
        Route::get('/paper', [ContributeController::class, 'createPaper'])->name('paper');
        Route::post('/paper', [ContributeController::class, 'storePaper'])->name('paper.store');
        
        Route::get('/creators', [ContributeController::class, 'createCreators'])->name('creators');
        Route::post('/creators', [ContributeController::class, 'storeCreators'])->name('creators.store');
        
        Route::get('/files', [ContributeController::class, 'createFiles'])->name('files');
        Route::post('/files', [ContributeController::class, 'storeFiles'])->name('files.store');
        
        Route::get('/keywords', [ContributeController::class, 'createKeywords'])->name('keywords');
        Route::post('/keywords', [ContributeController::class, 'storeKeywords'])->name('keywords.store');
        
        Route::get('/variable-info', [ContributeController::class, 'createVariableInfo'])->name('variable-info');
        Route::post('/variable-info', [ContributeController::class, 'storeVariableInfo'])->name('variable-info.store');
        
        Route::get('/descriptive', [ContributeController::class, 'createDescriptive'])->name('descriptive');
        Route::post('/submit', [ContributeController::class, 'submitDonation'])->name('submit');
    });
    
    // Contribute: Edit Approved Datasets
    Route::prefix('contribute/edit')->name('contribute.edit.')->group(function () {
        Route::get('/dataset/{dataset}/metadata', [ContributeController::class, 'editMetadata'])->name('metadata');
        Route::put('/dataset/{dataset}/metadata', [ContributeController::class, 'updateMetadata'])->name('metadata.update');
    });
    
    // Contribute: External Linking
    Route::prefix('contribute/linking')->name('contribute.linking.')->group(function () {
        Route::get('/', function () { return view('linking.policy'); })->name('index');
        Route::get('/metadata', [ContributeController::class, 'createLinkingMetadata'])->name('metadata');
        Route::post('/metadata', [ContributeController::class, 'storeLinkingMetadata'])->name('metadata.store');
        Route::get('/paper', [ContributeController::class, 'createLinkingPaper'])->name('paper');
        Route::post('/paper', [ContributeController::class, 'storeLinkingPaper'])->name('paper.store');
        Route::get('/creators', [ContributeController::class, 'createLinkingCreators'])->name('creators');
        Route::post('/creators', [ContributeController::class, 'storeLinkingCreators'])->name('creators.store');
        Route::get('/keywords', [ContributeController::class, 'createLinkingKeywords'])->name('keywords');
        Route::post('/keywords', [ContributeController::class, 'storeLinkingKeywords'])->name('keywords.store');
        Route::get('/variable-info', [ContributeController::class, 'createLinkingVariableInfo'])->name('variable-info');
        Route::post('/variable-info', [ContributeController::class, 'storeLinkingVariableInfo'])->name('variable-info.store');
        Route::get('/descriptive', [ContributeController::class, 'createLinkingDescriptive'])->name('descriptive');
        Route::post('/submit', [ContributeController::class, 'submitLinking'])->name('submit');
    });
    
    // External Link Routes
    Route::get('/contribute/external/form', [ContributeController::class, 'createExternalLink'])->name('contribute.external.form');
    Route::post('/contribute/external/submit', [ContributeController::class, 'submitExternalLink'])->name('contribute.external.submit');
});

// ===== 👮 ADMIN ROUTES (Admin & Superadmin Only) =====
Route::middleware(['auth', 'role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
    
    // ===== 📊 DASHBOARD & ANALYTICS =====
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
    Route::get('/analytics', [StatisticsController::class, 'analytics'])->name('analytics');
    
    // AJAX endpoints for dashboard charts
    Route::get('/api/stats/datasets', [StatisticsController::class, 'getDatasetStats'])->name('api.stats.datasets');
    Route::get('/api/stats/users', [StatisticsController::class, 'getUserStats'])->name('api.stats.users');
    Route::get('/api/stats/activity', [StatisticsController::class, 'getActivityStats'])->name('api.stats.activity');
    
    // ===== 🗂️ DATASETS CRUD (Main Admin Management) =====
    Route::prefix('datasets')->name('datasets.')->group(function () {
        // List & Create
        Route::get('/', [AdminDatasetController::class, 'index'])->name('index');
        Route::get('/create', [AdminDatasetController::class, 'create'])->name('create');
        Route::post('/', [AdminDatasetController::class, 'store'])->name('store');
        
        // View & Edit
        Route::get('/{dataset}', [AdminDatasetController::class, 'show'])->name('show');
        Route::get('/{dataset}/edit', [AdminDatasetController::class, 'edit'])->name('edit');
        Route::put('/{dataset}', [AdminDatasetController::class, 'update'])->name('update');
        Route::patch('/{dataset}', [AdminDatasetController::class, 'update'])->name('update.patch');
        
        // Delete
        Route::delete('/{dataset}', [AdminDatasetController::class, 'destroy'])->name('destroy');
        Route::post('/{dataset}/force-delete', [AdminDatasetController::class, 'forceDelete'])->name('force-delete');
        
        // Status Management
        Route::post('/{dataset}/approve', [AdminDatasetController::class, 'approve'])->name('approve');
        Route::post('/{dataset}/reject', [AdminDatasetController::class, 'reject'])->name('reject');
        Route::post('/{dataset}/pending', [AdminDatasetController::class, 'setPending'])->name('pending');
        Route::post('/{dataset}/available', [AdminDatasetController::class, 'setAvailable'])->name('available');
        Route::post('/{dataset}/deprecated', [AdminDatasetController::class, 'setDeprecated'])->name('deprecated');
        
        // Bulk Actions
        Route::post('/bulk-action', [AdminDatasetController::class, 'bulkAction'])->name('bulk-action');
        Route::post('/bulk-approve', [AdminDatasetController::class, 'bulkApprove'])->name('bulk-approve');
        Route::post('/bulk-reject', [AdminDatasetController::class, 'bulkReject'])->name('bulk-reject');
        Route::post('/bulk-delete', [AdminDatasetController::class, 'bulkDelete'])->name('bulk-delete');
        
        // Export & Import
        Route::get('/export', [AdminDatasetController::class, 'export'])->name('export');
        Route::post('/import', [AdminDatasetController::class, 'import'])->name('import');
        Route::get('/export-template', [AdminDatasetController::class, 'exportTemplate'])->name('export-template');
        
        // Dataset Components Management
        Route::prefix('{dataset}')->group(function () {
            // Files
            Route::get('/files', [AdminDatasetController::class, 'files'])->name('files');
            Route::post('/files', [AdminDatasetController::class, 'addFile'])->name('files.add');
            Route::delete('/files/{file}', [AdminDatasetController::class, 'removeFile'])->name('files.remove');
            
            // Keywords
            Route::get('/keywords', [AdminDatasetController::class, 'keywords'])->name('keywords');
            Route::post('/keywords', [AdminDatasetController::class, 'addKeyword'])->name('keywords.add');
            Route::delete('/keywords/{keyword}', [AdminDatasetController::class, 'removeKeyword'])->name('keywords.remove');
            
            // Contributors
            Route::get('/contributors', [AdminDatasetController::class, 'contributors'])->name('contributors');
            Route::post('/contributors', [AdminDatasetController::class, 'addContributor'])->name('contributors.add');
            Route::put('/contributors/{contributor}', [AdminDatasetController::class, 'updateContributor'])->name('contributors.update');
            Route::delete('/contributors/{contributor}', [AdminDatasetController::class, 'removeContributor'])->name('contributors.remove');
            
            // Variables
            Route::get('/variables', [AdminDatasetController::class, 'variables'])->name('variables');
            Route::post('/variables', [AdminDatasetController::class, 'addVariable'])->name('variables.add');
            Route::put('/variables/{variable}', [AdminDatasetController::class, 'updateVariable'])->name('variables.update');
            Route::delete('/variables/{variable}', [AdminDatasetController::class, 'removeVariable'])->name('variables.remove');
            
            // Papers/Citations
            Route::get('/papers', [AdminDatasetController::class, 'papers'])->name('papers');
            Route::post('/papers', [AdminDatasetController::class, 'addPaper'])->name('papers.add');
            Route::put('/papers/{paper}', [AdminDatasetController::class, 'updatePaper'])->name('papers.update');
            Route::delete('/papers/{paper}', [AdminDatasetController::class, 'removePaper'])->name('papers.remove');
            
            // Images
            Route::get('/images', [AdminDatasetController::class, 'images'])->name('images');
            Route::post('/images', [AdminDatasetController::class, 'addImage'])->name('images.add');
            Route::put('/images/{image}', [AdminDatasetController::class, 'updateImage'])->name('images.update');
            Route::delete('/images/{image}', [AdminDatasetController::class, 'removeImage'])->name('images.remove');
            
            // Reviews & Comments
            Route::get('/reviews', [AdminDatasetController::class, 'reviews'])->name('reviews');
            Route::post('/reviews', [AdminDatasetController::class, 'addReview'])->name('reviews.add');
            Route::put('/reviews/{review}', [AdminDatasetController::class, 'updateReview'])->name('reviews.update');
            Route::delete('/reviews/{review}', [AdminDatasetController::class, 'removeReview'])->name('reviews.remove');
        });
    });
    
    // ===== 📋 DATASET REVIEW (Alternative Review Flow) =====
    Route::prefix('datasets/review')->name('datasets.review.')->group(function () {
        Route::get('/', [DatasetReviewController::class, 'index'])->name('index');
        Route::get('/pending', [DatasetReviewController::class, 'pending'])->name('pending');
        Route::get('/{dataset}', [DatasetReviewController::class, 'show'])->name('show');
        Route::get('/{dataset}/review', [DatasetReviewController::class, 'review'])->name('review');
        Route::post('/{dataset}/approve', [DatasetReviewController::class, 'approve'])->name('approve');
        Route::post('/{dataset}/reject', [DatasetReviewController::class, 'reject'])->name('reject');
        Route::post('/{dataset}/request-changes', [DatasetReviewController::class, 'requestChanges'])->name('request-changes');
        Route::post('/bulk-approve', [DatasetReviewController::class, 'bulkApprove'])->name('bulk-approve');
        Route::post('/bulk-reject', [DatasetReviewController::class, 'bulkReject'])->name('bulk-reject');
    });
    
    // ===== 👥 USERS CRUD =====
    Route::prefix('users')->name('users.')->group(function () {
        // List & Create
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/create', [AdminUserController::class, 'create'])->name('create');
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        
        // View & Edit
        Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
        Route::patch('/{user}', [AdminUserController::class, 'updatePatch'])->name('update.patch');
        
        // Delete & Restore
        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/force-delete', [AdminUserController::class, 'forceDelete'])->name('force-delete');
        Route::post('/{user}/restore', [AdminUserController::class, 'restore'])->name('restore');
        
        // Account Actions
        Route::post('/{user}/toggle-ban', [AdminUserController::class, 'toggleBan'])->name('toggle-ban');
        Route::post('/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('toggle-active');
        Route::post('/{user}/verify-email', [AdminUserController::class, 'verifyEmail'])->name('verify-email');
        Route::post('/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('reset-password');
        Route::post('/{user}/impersonate', [AdminUserController::class, 'impersonate'])->name('impersonate');
        
        // Role Management
        Route::post('/{user}/update-role', [AdminUserController::class, 'updateRole'])->name('update-role');
        Route::post('/{user}/add-permission', [AdminUserController::class, 'addPermission'])->name('add-permission');
        Route::delete('/{user}/remove-permission/{permission}', [AdminUserController::class, 'removePermission'])->name('remove-permission');
        
        // Bulk Actions
        Route::post('/bulk-action', [AdminUserController::class, 'bulkAction'])->name('bulk-action');
        Route::post('/bulk-activate', [AdminUserController::class, 'bulkActivate'])->name('bulk-activate');
        Route::post('/bulk-ban', [AdminUserController::class, 'bulkBan'])->name('bulk-ban');
        Route::post('/bulk-delete', [AdminUserController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/bulk-export', [AdminUserController::class, 'bulkExport'])->name('bulk-export');
        
        // Export & Import
        Route::get('/export', [AdminUserController::class, 'export'])->name('export');
        Route::post('/import', [AdminUserController::class, 'import'])->name('import');
        
        // User Activity & Logs
        Route::get('/{user}/activity', [AdminUserController::class, 'activity'])->name('activity');
        Route::get('/{user}/datasets', [AdminUserController::class, 'userDatasets'])->name('datasets');
        Route::get('/{user}/downloads', [AdminUserController::class, 'userDownloads'])->name('downloads');
        Route::get('/{user}/reviews', [AdminUserController::class, 'userReviews'])->name('reviews');
        Route::get('/{user}/login-history', [AdminUserController::class, 'loginHistory'])->name('login-history');
    });
    
    // ===== 👤 USER MANAGEMENT (Alternative Flow) =====
    Route::prefix('users/manage')->name('users.manage.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
        Route::put('/{user}/role', [UserManagementController::class, 'updateRole'])->name('update-role');
        Route::post('/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{user}/send-notification', [UserManagementController::class, 'sendNotification'])->name('send-notification');
    });
    
    // ===== 🔑 KEYWORDS & TAXONOMY =====
    Route::prefix('keywords')->name('keywords.')->group(function () {
        Route::get('/', [AdminKeywordController::class, 'index'])->name('index');
        Route::get('/create', [AdminKeywordController::class, 'create'])->name('create');
        Route::post('/', [AdminKeywordController::class, 'store'])->name('store');
        Route::get('/{keyword}/edit', [AdminKeywordController::class, 'edit'])->name('edit');
        Route::put('/{keyword}', [AdminKeywordController::class, 'update'])->name('update');
        Route::delete('/{keyword}', [AdminKeywordController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [AdminKeywordController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export', [AdminKeywordController::class, 'export'])->name('export');
    });
    
    // ===== 📚 SUBJECT AREAS & CATEGORIES =====
    Route::prefix('subject-areas')->name('subject-areas.')->group(function () {
        Route::get('/', [AdminSubjectAreaController::class, 'index'])->name('index');
        Route::get('/create', [AdminSubjectAreaController::class, 'create'])->name('create');
        Route::post('/', [AdminSubjectAreaController::class, 'store'])->name('store');
        Route::get('/{area}/edit', [AdminSubjectAreaController::class, 'edit'])->name('edit');
        Route::put('/{area}', [AdminSubjectAreaController::class, 'update'])->name('update');
        Route::delete('/{area}', [AdminSubjectAreaController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [AdminSubjectAreaController::class, 'reorder'])->name('reorder');
    });
    
    // ===== 🎯 TASK TYPES =====
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [AdminTaskController::class, 'index'])->name('index');
        Route::get('/create', [AdminTaskController::class, 'create'])->name('create');
        Route::post('/', [AdminTaskController::class, 'store'])->name('store');
        Route::get('/{task}/edit', [AdminTaskController::class, 'edit'])->name('edit');
        Route::put('/{task}', [AdminTaskController::class, 'update'])->name('update');
        Route::delete('/{task}', [AdminTaskController::class, 'destroy'])->name('destroy');
    });
    
    // ===== 📄 LICENSES & DOI =====
    Route::prefix('licenses')->name('licenses.')->group(function () {
        Route::get('/', [AdminLicenseController::class, 'index'])->name('index');
        Route::get('/create', [AdminLicenseController::class, 'create'])->name('create');
        Route::post('/', [AdminLicenseController::class, 'store'])->name('store');
        Route::get('/{license}/edit', [AdminLicenseController::class, 'edit'])->name('edit');
        Route::put('/{license}', [AdminLicenseController::class, 'update'])->name('update');
        Route::delete('/{license}', [AdminLicenseController::class, 'destroy'])->name('destroy');
    });
    
    Route::prefix('dois')->name('dois.')->group(function () {
        Route::get('/', [AdminDoiController::class, 'index'])->name('index');
        Route::get('/create', [AdminDoiController::class, 'create'])->name('create');
        Route::post('/', [AdminDoiController::class, 'store'])->name('store');
        Route::get('/{doi}/edit', [AdminDoiController::class, 'edit'])->name('edit');
        Route::put('/{doi}', [AdminDoiController::class, 'update'])->name('update');
        Route::delete('/{doi}', [AdminDoiController::class, 'destroy'])->name('destroy');
    });
    
    // ===== 📁 FILES & MEDIA MANAGEMENT =====
    Route::prefix('files')->name('files.')->group(function () {
        Route::get('/', [AdminFileController::class, 'index'])->name('index');
        Route::get('/{file}', [AdminFileController::class, 'show'])->name('show');
        Route::delete('/{file}', [AdminFileController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [AdminFileController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/orphaned', [AdminFileController::class, 'orphaned'])->name('orphaned');
        Route::post('/cleanup', [AdminFileController::class, 'cleanup'])->name('cleanup');
    });
    
    // ===== 📝 REVIEWS & MODERATION =====
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [AdminReviewController::class, 'index'])->name('index');
        Route::get('/{review}', [AdminReviewController::class, 'show'])->name('show');
        Route::put('/{review}', [AdminReviewController::class, 'update'])->name('update');
        Route::delete('/{review}', [AdminReviewController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [AdminReviewController::class, 'bulkAction'])->name('bulk-action');
    });
    
    // ===== 📥 DOWNLOADS & ANALYTICS =====
    Route::prefix('downloads')->name('downloads.')->group(function () {
        Route::get('/', [AdminDownloadController::class, 'index'])->name('index');
        Route::get('/export', [AdminDownloadController::class, 'export'])->name('export');
        Route::get('/by-dataset/{dataset}', [AdminDownloadController::class, 'byDataset'])->name('by-dataset');
        Route::get('/by-user/{user}', [AdminDownloadController::class, 'byUser'])->name('by-user');
        Route::get('/trending', [AdminDownloadController::class, 'trending'])->name('trending');
    });
    
    // ===== ⚙️ SYSTEM SETTINGS =====
    Route::prefix('settings')->name('settings.')->group(function () {
        // General Settings
        Route::get('/general', [AdminSettingsController::class, 'general'])->name('general');
        Route::put('/general', [AdminSettingsController::class, 'updateGeneral'])->name('general.update');
        
        // Email/Notification Settings
        Route::get('/email', [AdminSettingsController::class, 'email'])->name('email');
        Route::put('/email', [AdminSettingsController::class, 'updateEmail'])->name('email.update');
        Route::post('/email/test', [AdminSettingsController::class, 'testEmail'])->name('email.test');
        
        // Security Settings
        Route::get('/security', [AdminSettingsController::class, 'security'])->name('security');
        Route::put('/security', [AdminSettingsController::class, 'updateSecurity'])->name('security.update');
        
        // API Settings
        Route::get('/api', [AdminSettingsController::class, 'api'])->name('api');
        Route::put('/api', [AdminSettingsController::class, 'updateApi'])->name('api.update');
        Route::post('/api/regenerate-key', [AdminSettingsController::class, 'regenerateApiKey'])->name('api.regenerate-key');
        
        // Backup Settings
        Route::get('/backup', [AdminSettingsController::class, 'backup'])->name('backup');
        Route::post('/backup/create', [AdminSettingsController::class, 'createBackup'])->name('backup.create');
        Route::post('/backup/restore/{backup}', [AdminSettingsController::class, 'restoreBackup'])->name('backup.restore');
        Route::delete('/backup/{backup}', [AdminSettingsController::class, 'deleteBackup'])->name('backup.delete');
    });
    
    // ===== 🔧 SYSTEM TOOLS & MAINTENANCE =====
    Route::prefix('tools')->name('tools.')->group(function () {
        // Cache Management
        Route::get('/cache', [AdminToolsController::class, 'cache'])->name('cache');
        Route::post('/cache/clear', [AdminToolsController::class, 'clearCache'])->name('cache.clear');
        Route::post('/cache/warm', [AdminToolsController::class, 'warmCache'])->name('cache.warm');
        
        // Database Tools
        Route::get('/database', [AdminToolsController::class, 'database'])->name('database');
        Route::post('/database/optimize', [AdminToolsController::class, 'optimizeDatabase'])->name('database.optimize');
        Route::post('/database/repair', [AdminToolsController::class, 'repairDatabase'])->name('database.repair');
        
        // Search Index
        Route::get('/search-index', [AdminToolsController::class, 'searchIndex'])->name('search-index');
        Route::post('/search-index/rebuild', [AdminToolsController::class, 'rebuildSearchIndex'])->name('search-index.rebuild');
        
        // Queue Management
        Route::get('/queue', [AdminToolsController::class, 'queue'])->name('queue');
        Route::post('/queue/restart', [AdminToolsController::class, 'restartQueue'])->name('queue.restart');
        Route::post('/queue/clear-failed', [AdminToolsController::class, 'clearFailedJobs'])->name('queue.clear-failed');
        
        // System Health
        Route::get('/health', [AdminToolsController::class, 'health'])->name('health');
        Route::get('/health/check', [AdminToolsController::class, 'runHealthCheck'])->name('health.check');
    });
    
    // ===== 📋 AUDIT LOGS =====
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [AdminLogController::class, 'index'])->name('index');
        Route::get('/{log}', [AdminLogController::class, 'show'])->name('show');
        Route::get('/user/{user}', [AdminLogController::class, 'byUser'])->name('by-user');
        Route::get('/dataset/{dataset}', [AdminLogController::class, 'byDataset'])->name('by-dataset');
        Route::get('/action/{action}', [AdminLogController::class, 'byAction'])->name('by-action');
        Route::get('/export', [AdminLogController::class, 'export'])->name('export');
        Route::delete('/clear', [AdminLogController::class, 'clear'])->name('clear');
    });
    
    // ===== 🔐 ROLES & PERMISSIONS =====
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [AdminRoleController::class, 'index'])->name('index');
        Route::get('/create', [AdminRoleController::class, 'create'])->name('create');
        Route::post('/', [AdminRoleController::class, 'store'])->name('store');
        Route::get('/{role}/edit', [AdminRoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [AdminRoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [AdminRoleController::class, 'destroy'])->name('destroy');
        Route::get('/{role}/permissions', [AdminRoleController::class, 'permissions'])->name('permissions');
        Route::post('/{role}/permissions', [AdminRoleController::class, 'updatePermissions'])->name('permissions.update');
    });
    
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [AdminPermissionController::class, 'index'])->name('index');
        Route::get('/create', [AdminPermissionController::class, 'create'])->name('create');
        Route::post('/', [AdminPermissionController::class, 'store'])->name('store');
        Route::get('/{permission}/edit', [AdminPermissionController::class, 'edit'])->name('edit');
        Route::put('/{permission}', [AdminPermissionController::class, 'update'])->name('update');
        Route::delete('/{permission}', [AdminPermissionController::class, 'destroy'])->name('destroy');
    });
    
    // ===== 📧 NOTIFICATIONS & MESSAGES =====
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
        Route::get('/create', [AdminNotificationController::class, 'create'])->name('create');
        Route::post('/', [AdminNotificationController::class, 'store'])->name('store');
        Route::get('/{notification}', [AdminNotificationController::class, 'show'])->name('show');
        Route::delete('/{notification}', [AdminNotificationController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-send', [AdminNotificationController::class, 'bulkSend'])->name('bulk-send');
        Route::get('/templates', [AdminNotificationController::class, 'templates'])->name('templates');
    });
    
    // ===== 🗂️ PAPERS & CITATIONS =====
    Route::prefix('papers')->name('papers.')->group(function () {
        Route::get('/', [AdminPaperController::class, 'index'])->name('index');
        Route::get('/create', [AdminPaperController::class, 'create'])->name('create');
        Route::post('/', [AdminPaperController::class, 'store'])->name('store');
        Route::get('/{paper}/edit', [AdminPaperController::class, 'edit'])->name('edit');
        Route::put('/{paper}', [AdminPaperController::class, 'update'])->name('update');
        Route::delete('/{paper}', [AdminPaperController::class, 'destroy'])->name('destroy');
        Route::post('/{paper}/upload', [AdminPaperController::class, 'upload'])->name('upload');
        Route::post('/bulk-import', [AdminPaperController::class, 'bulkImport'])->name('bulk-import');
    });
    
    // ===== 🔑 API KEYS & INTEGRATIONS =====
    Route::prefix('api-keys')->name('api-keys.')->group(function () {
        Route::get('/', [AdminApiKeyController::class, 'index'])->name('index');
        Route::get('/create', [AdminApiKeyController::class, 'create'])->name('create');
        Route::post('/', [AdminApiKeyController::class, 'store'])->name('store');
        Route::get('/{key}/edit', [AdminApiKeyController::class, 'edit'])->name('edit');
        Route::put('/{key}', [AdminApiKeyController::class, 'update'])->name('update');
        Route::delete('/{key}', [AdminApiKeyController::class, 'destroy'])->name('destroy');
        Route::post('/{key}/regenerate', [AdminApiKeyController::class, 'regenerate'])->name('regenerate');
        Route::get('/{key}/usage', [AdminApiKeyController::class, 'usage'])->name('usage');
    });
    
    // ===== 🌐 EXTERNAL INTEGRATIONS =====
    Route::prefix('integrations')->name('integrations.')->group(function () {
        Route::get('/', [AdminIntegrationController::class, 'index'])->name('index');
        Route::get('/{integration}/configure', [AdminIntegrationController::class, 'configure'])->name('configure');
        Route::post('/{integration}/save', [AdminIntegrationController::class, 'save'])->name('save');
        Route::post('/{integration}/test', [AdminIntegrationController::class, 'test'])->name('test');
        Route::post('/{integration}/sync', [AdminIntegrationController::class, 'sync'])->name('sync');
    });
    
    // ===== 📊 REPORTS =====
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminReportController::class, 'index'])->name('index');
        Route::get('/generate', [AdminReportController::class, 'generate'])->name('generate');
        Route::post('/generate', [AdminReportController::class, 'runReport'])->name('run');
        Route::get('/{report}', [AdminReportController::class, 'show'])->name('show');
        Route::get('/{report}/export', [AdminReportController::class, 'export'])->name('export');
        Route::delete('/{report}', [AdminReportController::class, 'destroy'])->name('destroy');
    });
    
    // ===== 🔄 IMPORT/EXPORT TOOLS =====
    Route::prefix('import-export')->name('import-export.')->group(function () {
        Route::get('/', [AdminImportExportController::class, 'index'])->name('index');
        Route::post('/datasets/import', [AdminImportExportController::class, 'importDatasets'])->name('datasets.import');
        Route::get('/datasets/export', [AdminImportExportController::class, 'exportDatasets'])->name('datasets.export');
        Route::post('/users/import', [AdminImportExportController::class, 'importUsers'])->name('users.import');
        Route::get('/users/export', [AdminImportExportController::class, 'exportUsers'])->name('users.export');
        Route::get('/template/{type}', [AdminImportExportController::class, 'downloadTemplate'])->name('template');
    });
    
    // ===== 🗑️ TRASH / SOFT DELETED ITEMS =====
    Route::prefix('trash')->name('trash.')->group(function () {
        Route::get('/', [AdminTrashController::class, 'index'])->name('index');
        Route::get('/datasets', [AdminTrashController::class, 'datasets'])->name('datasets');
        Route::get('/users', [AdminTrashController::class, 'users'])->name('users');
        Route::post('/{type}/{id}/restore', [AdminTrashController::class, 'restore'])->name('restore');
        Route::delete('/{type}/{id}/force-delete', [AdminTrashController::class, 'forceDelete'])->name('force-delete');
        Route::post('/bulk-restore', [AdminTrashController::class, 'bulkRestore'])->name('bulk-restore');
        Route::post('/bulk-force-delete', [AdminTrashController::class, 'bulkForceDelete'])->name('bulk-force-delete');
    });
    
    // ===== 🎨 UI/THEME SETTINGS =====
    Route::prefix('appearance')->name('appearance.')->group(function () {
        Route::get('/', [AdminAppearanceController::class, 'index'])->name('index');
        Route::put('/theme', [AdminAppearanceController::class, 'updateTheme'])->name('theme.update');
        Route::put('/branding', [AdminAppearanceController::class, 'updateBranding'])->name('branding.update');
        Route::post('/upload-logo', [AdminAppearanceController::class, 'uploadLogo'])->name('upload-logo');
        Route::post('/upload-favicon', [AdminAppearanceController::class, 'uploadFavicon'])->name('upload-favicon');
    });
    
    // ===== 🌍 LOCALIZATION =====
    Route::prefix('localization')->name('localization.')->group(function () {
        Route::get('/', [AdminLocalizationController::class, 'index'])->name('index');
        Route::get('/languages', [AdminLocalizationController::class, 'languages'])->name('languages');
        Route::post('/languages', [AdminLocalizationController::class, 'addLanguage'])->name('languages.add');
        Route::put('/languages/{lang}', [AdminLocalizationController::class, 'updateLanguage'])->name('languages.update');
        Route::delete('/languages/{lang}', [AdminLocalizationController::class, 'removeLanguage'])->name('languages.remove');
        Route::get('/translations', [AdminLocalizationController::class, 'translations'])->name('translations');
        Route::put('/translations/{key}', [AdminLocalizationController::class, 'updateTranslation'])->name('translations.update');
    });
});
// ===== 🔄 HELPER & ALIASES =====
Route::redirect('/dashboard', '/')->name('dashboard.redirect');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::get('/profile/datasets', [ProfileController::class, 'datasets'])->name('profile.datasets');
Route::middleware(['auth', 'role:admin,superadmin'])->get('/admin', function () {
    return redirect()->route('admin.dashboard');
})->name('admin.quick-access');

// ===== 🔐 LARAVEL BREEZE AUTH =====
require __DIR__.'/auth.php';