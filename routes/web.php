<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('tasks', TaskController::class);
});

require __DIR__.'/auth.php';

// PrivacyLib uses the existing web session and Laravel CSRF middleware.
Route::middleware('auth')->prefix('gdpr')->name('gdpr.')->group(function () {
    Route::get('/', [\App\Http\Controllers\GdprController::class, 'page'])->name('index');
    Route::get('/csrf', [\App\Http\Controllers\GdprController::class, 'csrf'])->name('csrf');
    Route::patch('/consent', [\App\Http\Controllers\GdprController::class, 'consent'])->name('consent');
    Route::post('/{policy}', [\App\Http\Controllers\GdprController::class, 'evaluate'])->name('evaluate');
});
