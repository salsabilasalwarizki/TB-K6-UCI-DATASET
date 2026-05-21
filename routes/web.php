<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    DatasetController,
    ProfileController,
    ContributeController,
    SocialAuthController
};
use App\Http\Controllers\Admin\AdminDashboardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| Routes are grouped by access level for better organization.
|
*/

// ===== 🌐 PUBLIC ROUTES (No Auth Required) =====

// Home & About Pages
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('about')->group(function () {
    Route::get('/', [HomeController::class, 'about'])->name('about');
    Route::get('/who-we-are', [HomeController::class, 'whoWeAre'])->name('about.who-we-are');
    Route::get('/citation', [HomeController::class, 'citation'])->name('about.citation');
    Route::get('/contact', [HomeController::class, 'contact'])->name('about.contact');
});

// Datasets Routes
Route::get('/datasets', [DatasetController::class, 'index'])->name('datasets.index');
Route::get('/datasets/{dataset}', [DatasetController::class, 'show'])->name('datasets.show');
Route::get('/datasets/{dataset}/files/{file}/download', [DatasetController::class, 'download'])
     ->name('datasets.download')
     ->middleware('throttle:30,1'); // Rate limit: 30 downloads per minute
// ===== 📊 DATASET TRACKING & INTERACTION ROUTES =====
Route::prefix('datasets')->name('datasets.')->group(function() {
    // Track view (AJAX endpoint)
    Route::post('/{dataset}/track-view', [DatasetController::class, 'trackView'])
        ->name('track-view')
        ->middleware('throttle:60,1'); // Max 60 requests per minute per IP
    
    // Save to collection (requires auth)
    Route::post('/{dataset}/save', [DatasetController::class, 'save'])
        ->name('save')
        ->middleware(['auth', 'throttle:30,1']);
    
    // Quick preview API (optional)
    Route::get('/{dataset}/preview', [DatasetController::class, 'preview'])
        ->name('preview');
});
// Search (redirect to datasets with query params)
Route::get('/search', function (\Illuminate\Http\Request $request) {
    return redirect()->route('datasets.index', $request->only('q', 'task', 'area', 'instances'));
})->name('search');

// Contribute Policy (Public gate before donation form)
Route::get('/contribute', [ContributeController::class, 'policy'])->name('contribute.policy');


// ===== 🔐 AUTHENTICATED ROUTES (Login Required) =====
Route::middleware('auth')->group(function () {
    
   // Profile Routes
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    Route::get('/datasets', [ProfileController::class, 'datasets'])->name('datasets');
    Route::get('/dataset/{dataset}', [ProfileController::class, 'showDataset'])->name('dataset.show');
    Route::put('/dataset/{dataset}/status', [ProfileController::class, 'updateDatasetStatus'])->name('dataset.update-status');
    Route::get('/edits', [ProfileController::class, 'edits'])->name('edits');
});
    
    Route::middleware('auth')->prefix('contribute/donation')->name('contribute.')->group(function () {
    // Page 1: Metadata
    Route::get('/metadata', [ContributeController::class, 'createMetadata'])->name('metadata');
    Route::post('/metadata', [ContributeController::class, 'storeMetadata'])->name('metadata.store');
    
    // Page 2: Paper
    Route::get('/paper', [ContributeController::class, 'createPaper'])->name('paper');
    Route::post('/paper', [ContributeController::class, 'storePaper'])->name('paper.store');
    
    // Page 3: Creators
    Route::get('/creators', [ContributeController::class, 'createCreators'])->name('creators');
    Route::post('/creators', [ContributeController::class, 'storeCreators'])->name('creators.store');
    
    // Page 4: Files
    Route::get('/files', [ContributeController::class, 'createFiles'])->name('files');
    Route::post('/files', [ContributeController::class, 'storeFiles'])->name('files.store');
    
    // Page 5: Keywords ⭐ BARU
    Route::get('/keywords', [ContributeController::class, 'createKeywords'])->name('keywords');
    Route::post('/keywords', [ContributeController::class, 'storeKeywords'])->name('keywords.store');
    
     // Page 6: Variable Information ⭐ BARU
    Route::get('/variable-info', [ContributeController::class, 'createVariableInfo'])->name('variable-info');
    Route::post('/variable-info', [ContributeController::class, 'storeVariableInfo'])->name('variable-info.store');
    
   // Page 7: Descriptive Questions & Submit ⭐ BARU
    Route::get('/descriptive', [ContributeController::class, 'createDescriptive'])->name('descriptive');
    Route::post('/submit', [ContributeController::class, 'submitDonation'])->name('submit');
});
});


// ===== 🔑 SOCIALITE AUTH ROUTES (Public) =====
Route::prefix('auth')->group(function () {
    
    // Google OAuth
    Route::get('/google/redirect', [SocialAuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
    
    // GitHub OAuth
    Route::get('/github/redirect', [SocialAuthController::class, 'redirectToGithub'])->name('github.login');
    Route::get('/github/callback', [SocialAuthController::class, 'handleGithubCallback']);
});


// ===== 🔄 HELPER ROUTES =====

// Fix: Redirect /dashboard to home (prevent Breeze 404 error)
Route::redirect('/dashboard', '/')->name('dashboard');

// ===== 🔄 ROUTE ALIASES (Backward Compatibility) =====
// Alias untuk route yang masih dipanggil dengan nama lama di views
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::get('/profile/datasets', [ProfileController::class, 'datasets'])->name('profile.datasets');

// Linking Policy
Route::get('/contribute/linking', function () {
    return view('linking.policy');
})->name('contribute.linking');

// External Link Form (after agreeing)
Route::get('/contribute/external/form', [ContributeController::class, 'createExternalLink'])
    ->name('contribute.external.form')
    ->middleware('auth');

// External Link Submit
Route::post('/contribute/external/submit', [ContributeController::class, 'submitExternalLink'])
    ->name('contribute.external.submit')
    ->middleware('auth');

// Linking Metadata
Route::get('/contribute/linking/metadata', [ContributeController::class, 'createLinkingMetadata'])
    ->name('contribute.linking.metadata')
    ->middleware('auth');
    
Route::post('/contribute/linking/metadata', [ContributeController::class, 'storeLinkingMetadata'])
    ->name('contribute.linking.metadata.store')
    ->middleware('auth');

// Linking Paper (Page 2)
Route::get('/contribute/linking/paper', [ContributeController::class, 'createLinkingPaper'])
    ->name('contribute.linking.paper')
    ->middleware('auth');
    
Route::post('/contribute/linking/paper', [ContributeController::class, 'storeLinkingPaper'])
    ->name('contribute.linking.paper.store')
    ->middleware('auth');

// Linking Creators (Page 3)
Route::get('/contribute/linking/creators', [ContributeController::class, 'createLinkingCreators'])
    ->name('contribute.linking.creators')
    ->middleware('auth');
    
Route::post('/contribute/linking/creators', [ContributeController::class, 'storeLinkingCreators'])
    ->name('contribute.linking.creators.store')
    ->middleware('auth');

// Linking Keywords (Page 4)
Route::get('/contribute/linking/keywords', [ContributeController::class, 'createLinkingKeywords'])
    ->name('contribute.linking.keywords')
    ->middleware('auth');
    
Route::post('/contribute/linking/keywords', [ContributeController::class, 'storeLinkingKeywords'])
    ->name('contribute.linking.keywords.store')
    ->middleware('auth');

// Linking Variable Info (Page 5)
Route::get('/contribute/linking/variable-info', [ContributeController::class, 'createLinkingVariableInfo'])
    ->name('contribute.linking.variable-info')
    ->middleware('auth');
    
Route::post('/contribute/linking/variable-info', [ContributeController::class, 'storeLinkingVariableInfo'])
    ->name('contribute.linking.variable-info.store')
    ->middleware('auth');

// Linking Descriptive (Page 6 & Submit)
Route::get('/contribute/linking/descriptive', [ContributeController::class, 'createLinkingDescriptive'])
    ->name('contribute.linking.descriptive')
    ->middleware('auth');
    
Route::post('/contribute/linking/submit', [ContributeController::class, 'submitLinking'])
    ->name('contribute.linking.submit')
    ->middleware('auth');

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
// routes/web.php
Route::post('/admin/papers/upload', [PaperController::class, 'upload'])->name('admin.papers.upload');
// ===== 🔐 LARAVEL BREEZE AUTH ROUTES =====
// DO NOT EDIT - Auto-generated by Laravel Breeze
require __DIR__.'/auth.php';
// Admin Routes
require __DIR__.'/admin.php';

// ===== ADMIN QUICK ACCESS =====
Route::middleware(['auth', 'admin'])->get('/admin', function () {
    return redirect()->route('admin.dashboard');
});
