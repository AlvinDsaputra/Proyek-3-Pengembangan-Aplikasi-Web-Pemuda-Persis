<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Modules\SharedCore\Http\Controllers\OrganizationalPeriodController;
use Modules\SharedCore\Http\Controllers\SignatureController;
use Modules\SuratKeluar\Http\Controllers\LetterDestinationCodeController;
use Modules\SuratKeluar\Http\Controllers\LetterTemplateController;
use Modules\SuratKeluar\Http\Controllers\LetterTypeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Surat Keluar — Master Jenis Surat, Kode Tujuan, & Template (DEV-02)
    Route::prefix('surat-keluar')->name('surat-keluar.')->group(function () {
        // Endpoint JSON Fetch Template (dapat diakses oleh Sekretaris maupun Bidang Administrasi)
        Route::get('/api/letter-types/{letter_type}/templates', [LetterTemplateController::class, 'getByLetterType'])
            ->name('api.templates.by-type');

        // CRUD Master Data (Dikunci Policy khusus Bidang Administrasi)
        Route::prefix('master')->name('master.')->group(function () {
            Route::resource('letter-types', LetterTypeController::class)->except(['show']);
            Route::resource('destination-codes', LetterDestinationCodeController::class)->except(['show']);
            Route::resource('templates', LetterTemplateController::class)->except(['show']);
        });
    });
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
