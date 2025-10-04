<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Admin\TugasKumpulController;
use App\Http\Controllers\User\UserProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman welcome (public)
Route::get('/', function () {
    return view('welcome');
});

// Auth routes: login, logout, register
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Route untuk akses file tugas (dari storage/app/tugas_files)
Route::get('/tugas_files/{filename}', function ($filename) {
    $path = storage_path('app/tugas_files/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    $file = file_get_contents($path);
    $type = mime_content_type($path);

    return Response::make($file, 200)->header("Content-Type", $type);
})->name('tugas_files.show');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);

    Route::get('absensi/scan', [AbsensiController::class, 'scan'])->name('absensi.scan');
    Route::post('absensi/scan', [AbsensiController::class, 'scanStore'])->name('absensi.scan.store');

    // Export ke Excel sesuai filter
    Route::get('absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');

    Route::resource('absensi', AbsensiController::class)->where(['absensi' => '[0-9]+']);

    Route::get('presensi', [PresensiController::class, 'index'])->name('presensi.index');

    Route::resource('tugas', \App\Http\Controllers\Admin\TugasController::class);

    Route::get('tugas-kumpul', [TugasKumpulController::class, 'index'])->name('tugas_kumpul.index');
    Route::get('tugas-kumpul/export', [TugasKumpulController::class, 'export'])->name('tugas_kumpul.export');

    Route::get('tugas-kumpul/{tugasKumpul}/edit', [TugasKumpulController::class, 'edit'])->name('tugas_kumpul.edit');
    Route::put('tugas-kumpul/{tugasKumpul}', [TugasKumpulController::class, 'update'])->name('tugas_kumpul.update');
});

Route::middleware(['auth'])->group(function() {
    // Halaman profile user
    Route::get('profile', [UserProfileController::class, 'index'])->name('profile.index');
});

// Group route untuk user (role user)
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});

// Contoh route untuk user presensi
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/presensi', [App\Http\Controllers\User\PresensiController::class, 'index'])->name('presensi.index');

    Route::get('tugas', [App\Http\Controllers\User\TugasController::class, 'index'])->name('tugas.index');
    Route::get('tugas/{tugas}', [App\Http\Controllers\User\TugasController::class, 'show'])->name('tugas.show');

    // Route untuk upload tugas hasil pengerjaan
    Route::post('tugas/{tugas}/kumpul', [App\Http\Controllers\User\TugasController::class, 'upload'])->name('tugas.upload');
});

Route::get('/gemini', function () {
    return view('layouts.gemini');
})->name('gemini');

// Jetstream dashboard default (bisa dihapus kalau tidak pakai)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
