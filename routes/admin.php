<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes (MINIMAL & AMAN)
|--------------------------------------------------------------------------
| Tidak ada 'use' statement controller Admin untuk menghindari error autoloading.
| Tambahkan kembali hanya setelah file controller benar-benar dibuat.
*/

Route::prefix('admin')
     ->name('admin.')
     ->middleware(['auth', 'admin'])
     ->group(function () {
    
    // ✅ Dashboard pakai closure (tidak butuh controller file)
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    /*
    // 🔒 AREA AMAN UNTUK ROUTE MASA DEPAN
    // Uncomment baris di bawah HANYA setelah kamu membuat file controller-nya:
    
    // Route::prefix('users')->name('users.')->group(function () {
    //     Route::get('/', [UserController::class, 'index'])->name('index');
    // });
    */
});