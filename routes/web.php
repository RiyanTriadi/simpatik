<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\ComplaintController;
use App\Http\Controllers\Public\AspirationController;
use App\Http\Controllers\Admin\Dashboard;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\AspirationController as AdminAspirationController;

Route::get('/', function () {return view('public.index');})->name('public.index');
Route::resource('pengaduan', ComplaintController::class)->names('public.pengaduan');
Route::resource('aspirasi', AspirationController::class)->names('public.aspirasi');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('dashboard', [Dashboard::class, 'index'])->name('dashboard');
    Route::get('pengaduan/verifikasi', [AdminComplaintController::class, 'verification'])->name('pengaduan.verification');
    Route::resource('pengaduan', AdminComplaintController::class)->names('pengaduan');
    Route::get('aspirasi/tindak-lanjut', [AdminAspirationController::class, 'followUp'])->name('aspirasi.follow-up');
    Route::resource('aspirasi', AdminAspirationController::class)->names('aspirasi');

    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('kategori', \App\Http\Controllers\Admin\Master\CategoryController::class)
            ->names('kategori')
            ->parameters(['kategori' => 'category']);
    });
});
