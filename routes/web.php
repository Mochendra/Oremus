<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\DashboardController; // Add this line to import the DashboardController
use App\Models\Dokumen;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

// Group routes that require authentication
Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ensure the DokumenController route is correctly defined
    Route::post('/dokumen', [DokumenController::class, 'store'])->name('dokumen.store');
    Route::get('/dokumen', [DokumenController::class, 'index']);
    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
    Route::get('/dokumen/view/{id}', [DokumenController::class, 'view'])->name('dokumen.view');
    Route::get('/dokumen/view-pdf/{id}', [DokumenController::class, 'viewPdf'])->name('dokumen.view-pdf');
    Route::get('/dokumen/download/{id}', [DokumenController::class, 'download'])->name('dokumen.download');
    // If you want to handle POST requests for the dashboard, define it like this:
    Route::post('/dashboard', [DashboardController::class, 'index'])->name('dashboard.store');
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

    // Ensure the posts routes are correctly defined
    Route::get('/posts', 'PostController@index')->name('posts');
    Route::post('/posts', 'PostController@store')->name('posts.store');

    Route::get('/view-pdf/{id}', [DokumenController::class, 'handlePdf']);
    Route::get('/download-pdf/{id}', [DokumenController::class, 'handlePdf'])->defaults('action', 'download');


    // Route for editing expired documents
    Route::get('/dokumen/{id}/edit-expired', [DokumenController::class, 'editExpired'])->name('dokumen.edit-expired');
    Route::put('/dokumen/{id}/update-expired', [DokumenController::class, 'updateExpired'])->name('dokumen.update-expired');
    Route::post('/dokumen/{id}/update', [DokumenController::class, 'update'])->name('dokumen.update');

    Route::get('/dokumen/export/excel', [DokumenController::class, 'exportExcel'])->name('dokumen.export.excel');
    Route::get('/dokumen/history', [DokumenController::class, 'showHistory'])->name('dokumen.history');

});

Route::group(['middleware' => 'role:admin,user'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // Ensure this route has a name
});

Route::get('/notifikasi', [DokumenController::class, 'notifikasi']);
// Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
Route::get('/notifications/{notification}', [DokumenController::class, 'showNotification'])->name('notifications.show');
Route::post('/notifications/{id}/mark-as-read', [DokumenController::class, 'markAsRead'])->name('notifications.markAsRead');
// Load authentication routes
require __DIR__ . '/auth.php';
