<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KeluargaController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\SawController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::POST('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::POST('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // 1. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Manajemen Pengguna
    Route::resource('pengguna', UserController::class);

    // 3. Data Kepala Keluarga
    Route::resource('keluarga', KeluargaController::class);

    // 4. Kriteria & Sub-Kriteria
    Route::get('/kriteria', [KriteriaController::class, 'index'])->name('kriteria.index');
    Route::post('/kriteria/bobot', [KriteriaController::class, 'updateBobot'])->name('kriteria.update-bobot');
    Route::post('/kriteria/bobot-update', [KriteriaController::class, 'updateBobot'])->name('kriteria.bobot.update');
    Route::post('/kriteria/{kriteria}/sub', [KriteriaController::class, 'storeSubKriteria'])->name('kriteria.sub.store');
    Route::put('/sub-kriteria/{subKriteria}', [KriteriaController::class, 'updateSubKriteria'])->name('sub_kriteria.update');
    Route::delete('/sub-kriteria/{subKriteria}', [KriteriaController::class, 'destroySubKriteria'])->name('sub_kriteria.destroy');

    // Periode Management
    Route::get('/periode', [PeriodeController::class, 'index'])->name('periode.index');
    Route::post('/periode', [PeriodeController::class, 'store'])->name('periode.store');
    Route::post('/periode/{periode}/activate', [PeriodeController::class, 'activate'])->name('periode.activate');
    Route::delete('/periode/{periode}', [PeriodeController::class, 'destroy'])->name('periode.destroy');

    // 5. Penilaian Calon Penerima
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
    Route::post('/penilaian/bulk-auto', [PenilaianController::class, 'bulkAutoFill'])->name('penilaian.bulk-auto');
    Route::post('/penilaian/bulk', [PenilaianController::class, 'bulkAutoFill'])->name('penilaian.bulk');
    Route::get('/penilaian/{keluarga}/edit', [PenilaianController::class, 'edit'])->name('penilaian.edit');
    Route::match(['put', 'post'], '/penilaian/{keluarga}', [PenilaianController::class, 'update'])->name('penilaian.update');

    // 6. Menu Normalisasi
    Route::get('/spk/normalisasi', [SawController::class, 'normalisasi'])->name('spk.normalisasi');

    // 7. Menu Perhitungan SAW
    Route::get('/spk/perhitungan', [SawController::class, 'perhitungan'])->name('spk.perhitungan');

    // 8. Menu Perankingan
    Route::get('/spk/perangkingan', [SawController::class, 'perangkingan'])->name('spk.perangkingan');
    Route::POST('/spk/simpan', [SawController::class, 'simpanHasil'])->name('spk.simpan');

    // 9. Laporan & Cetak PDF
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pdf', [LaporanController::class, 'downloadPdf'])->name('laporan.pdf');
});
