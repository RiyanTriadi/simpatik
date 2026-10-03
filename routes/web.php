<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\ComplaintController as PublicComplaint;
use App\Http\Controllers\Public\AspirationController as PublicAspiration;
use App\Http\Controllers\Admin\Dashboard;
use App\Http\Controllers\Admin\InboxController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\AspirationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\Master\CategoryController;
use App\Http\Controllers\Admin\Master\UnitController;

// ==================== PUBLIC ====================
Route::get('/', function () {
    // User sudah login? Redirect ke dashboard role-nya
    if (auth()->check()) {
        return redirect('/' . auth()->user()->role . '/dashboard');
    }
    return view('public.index');
})->name('public.index');

Route::resource('pengaduan', PublicComplaint::class)->names('public.pengaduan');
Route::resource('aspirasi', PublicAspiration::class)->names('public.aspirasi');

// ==================== AUTHENTICATED ====================
foreach (['admin', 'staff', 'petugas'] as $role) {
    Route::prefix($role)
        ->name("{$role}.")
        ->middleware(['auth', "role:{$role}"])
        ->group(function () use ($role) {

            // === DASHBOARD (semua role) ===
            Route::get('dashboard', [Dashboard::class, 'index'])->name('dashboard');

            // === PROFILE (semua role) ===
            Route::resource('profile', ProfileController::class)
                ->only(['edit', 'update'])
                ->parameters(['profile' => 'id']);

            // === INBOX (semua role) ===
            Route::get('inbox', [InboxController::class, 'index'])->name('inbox.index');

            // === PENGADUAN ===
            Route::prefix('pengaduan')->name('pengaduan.')->group(function () use ($role) {
                // Route statis dulu
                if (in_array($role, ['admin', 'staff'])) {
                    Route::get('/verifikasi/list', [ComplaintController::class, 'verification'])->name('verification');
                    Route::get('/assign/list', [ComplaintController::class, 'assign'])->name('assign');
                }

                // Route dinamis
                Route::get('/', [ComplaintController::class, 'index'])->name('index');
                Route::get('/{complaint}', [ComplaintController::class, 'show'])->name('show');
                Route::put('/{complaint}', [ComplaintController::class, 'update'])->name('update');

                if (in_array($role, ['admin', 'staff'])) {
                    Route::put('/{complaint}/assign', [ComplaintController::class, 'assignStore'])->name('assign.store');
                }

                if ($role === 'admin') {
                    Route::delete('/{complaint}', [ComplaintController::class, 'destroy'])->name('destroy');
                }
            });

            // === ASPIRASI ===
            Route::prefix('aspirasi')->name('aspirasi.')->group(function () use ($role) {
                if (in_array($role, ['admin', 'staff'])) {
                    Route::get('/tindak-lanjut/list', [AspirationController::class, 'followUp'])->name('follow-up');
                }

                Route::get('/', [AspirationController::class, 'index'])->name('index');
                Route::get('/{aspiration}', [AspirationController::class, 'show'])->name('show');
                Route::put('/{aspiration}', [AspirationController::class, 'update'])->name('update');

                if (in_array($role, ['admin', 'staff'])) {
                    Route::put('/{aspiration}/assign', [AspirationController::class, 'assignStore'])->name('assign.store');
                }

                if ($role === 'admin') {
                    Route::delete('/{aspiration}', [AspirationController::class, 'destroy'])->name('destroy');
                }
            });

            // === KHUSUS STAFF: Daftar Petugas (read-only) ===
            if ($role === 'staff') {
                Route::get('petugas', [UserController::class, 'officers'])->name('petugas.index');
            }

            // === KHUSUS ADMIN ===
            if ($role === 'admin') {
                Route::resource('users', UserController::class)->except(['show']);

                Route::prefix('master')->name('master.')->group(function () {
                    Route::resource('kategori', CategoryController::class)
                        ->names('kategori')
                        ->parameters(['kategori' => 'category'])
                        ->except(['create', 'edit', 'show']);

                    Route::resource('unit-kerja', UnitController::class)
                        ->names('unit-kerja')
                        ->parameters(['unit-kerja' => 'unit'])
                        ->except(['create', 'edit', 'show']);
                });
            }
        });
}