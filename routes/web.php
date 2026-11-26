<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Modules\SharedCore\Http\Controllers\OrganizationalPeriodController;
use Modules\SharedCore\Http\Controllers\SignatureController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
