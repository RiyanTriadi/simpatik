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
use Illuminate\Notifications\DatabaseNotification;

// PUBLIC
Route::get('/', function () {
    // User sudah login? Redirect ke dashboard role-nya
    if (auth()->check()) {
        return redirect('/' . auth()->user()->role . '/dashboard');
    }
    return view('public.index');
})->name('public.index');

Route::resource('pengaduan', PublicComplaint::class)
    ->except(['destroy'])
    ->names('public.pengaduan');
Route::resource('aspirasi', PublicAspiration::class)
    ->except(['destroy'])
    ->names('public.aspirasi');

// AUTHENTICATED
foreach (['admin', 'staff', 'petugas'] as $role) {
    Route::prefix($role)
        ->name("{$role}.")
        ->middleware(['auth', "role:{$role}"])
        ->group(function () use ($role) {

            // DASHBOARD (semua role)
            Route::get('dashboard', [Dashboard::class, 'index'])->name('dashboard');

            // PROFILE (semua role)
            Route::resource('profile', ProfileController::class)
                ->only(['edit', 'update'])
                ->parameters(['profile' => 'id']);

            // INBOX (semua role)
            Route::get('inbox', [InboxController::class, 'index'])->name('inbox.index');
            Route::post('notifications/read-all', function () {
                auth()->user()->unreadNotifications->markAsRead();
                return redirect()->back();
            })->name('notifications.read-all');
            Route::post('notifications/{notification}/read', function (DatabaseNotification $notification) {
                abort_unless((string) $notification->notifiable_id === (string) auth()->id(), 403);
                $notification->markAsRead();
                return redirect()->back();
            })->name('notifications.read');

            // PENGADUAN
            Route::prefix('pengaduan')->name('pengaduan.')->group(function () use ($role) {
                // Route statis dulu
                if (in_array($role, ['admin', 'staff'])) {
                    Route::get('/verifikasi/list', [ComplaintController::class, 'verification'])->name('verification');
                    Route::get('/assign/list', [ComplaintController::class, 'assign'])->name('assign');
                }

                // Route dinamis
                Route::get('/', [ComplaintController::class, 'index'])->name('index');
                Route::get('/{complaint:ticket_number}/attachment/download', [ComplaintController::class, 'downloadAttachment'])->name('attachment.download');
                Route::get('/{complaint:ticket_number}', [ComplaintController::class, 'show'])->name('show');
                Route::put('/{complaint:ticket_number}', [ComplaintController::class, 'update'])->name('update');

                if (in_array($role, ['admin', 'staff'])) {
                    Route::put('/{complaint:ticket_number}/assign', [ComplaintController::class, 'assignStore'])->name('assign.store');
                }
            });

            // ASPIRASI (khusus admin dan staff)
            if (in_array($role, ['admin', 'staff'])) {
                Route::prefix('aspirasi')->name('aspirasi.')->group(function () {
                    Route::get('/tindak-lanjut/list', [AspirationController::class, 'followUp'])->name('follow-up');
                    Route::get('/', [AspirationController::class, 'index'])->name('index');
                    Route::get('/{aspiration:ticket_number}/attachment/download', [AspirationController::class, 'downloadAttachment'])->name('attachment.download');
                    Route::get('/{aspiration:ticket_number}', [AspirationController::class, 'show'])->name('show');
                    Route::put('/{aspiration:ticket_number}', [AspirationController::class, 'update'])->name('update');
                });
            }

            // KHUSUS STAFF: Daftar Petugas (read-only)
            if ($role === 'staff') {
                Route::get('petugas', [UserController::class, 'officers'])->name('petugas.index');
            }

            // KHUSUS ADMIN
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
