<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Modules\SharedCore\Http\Controllers\DashboardController;
use Modules\SharedCore\Http\Controllers\OrganizationalPeriodController;
use Modules\SharedCore\Http\Controllers\SignatureController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'redirectByRole'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->middleware(['auth', 'role:superadmin'])
    ->name('admin.dashboard');

Route::get('/ketua/dashboard', [DashboardController::class, 'ketua'])
    ->middleware(['auth', 'role:ketua'])
    ->name('ketua.dashboard');

Route::get('/sekretaris/dashboard', [DashboardController::class, 'sekretaris'])
    ->middleware(['auth', 'role:sekretaris'])
    ->name('sekretaris.dashboard');

Route::get('/bidang-administrasi/dashboard', [DashboardController::class, 'bidangAdministrasi'])
    ->middleware(['auth', 'role:bidang_administrasi'])
    ->name('bidang-administrasi.dashboard');

Route::get('/anggota/dashboard', [DashboardController::class, 'anggota'])
    ->middleware(['auth', 'role:anggota'])
    ->name('anggota.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'permission:shared.periods.manage'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::resource('periods', OrganizationalPeriodController::class)
            ->only(['index', 'create', 'store']);
        Route::patch('periods/{period}/activate', [OrganizationalPeriodController::class, 'activate'])
            ->name('periods.activate');
    });

Route::middleware(['auth', 'permission:shared.signature.upload'])
    ->prefix('tanda-tangan')->name('shared.signature.')
    ->group(function () {
        Route::get('/', [SignatureController::class, 'edit'])->name('edit');
        Route::post('/', [SignatureController::class, 'store'])->name('store');
        Route::get('/preview', [SignatureController::class, 'preview'])->name('preview');
        Route::delete('/', [SignatureController::class, 'destroy'])->name('destroy');
    });

require __DIR__.'/auth.php';
