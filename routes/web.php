<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\ComplaintController;
use App\Http\Controllers\Public\AspirationController;
use App\Http\Controllers\Admin\Dashboard;
use App\Http\Controllers\Admin\ComplaintsController;

Route::get('/', function () {return view('public.index');})->name('public.index');
Route::resource('pengaduan', ComplaintController::class)->names('public.pengaduan');
Route::resource('aspirasi', AspirationController::class)->names('public.aspirasi');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [Dashboard::class, 'index'])->name('dashboard');
    Route::resource('pengaduan', ComplaintsController::class)->names('pengaduan');
});
