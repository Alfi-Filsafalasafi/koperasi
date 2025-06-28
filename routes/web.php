<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\TransaksiSimpananController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\TransaksiPinjamanController;
use App\Http\Controllers\JurnalKasKeluarController;
use App\Http\Controllers\JurnalKasMasukController;
use App\Http\Controllers\LaporanNisbahController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'pimpinan'])->group(function () {
    Route::resource('anggota', AnggotaController::class);
    Route::resource('simpanan', SimpananController::class);
    Route::resource('transaksi-simpanan', TransaksiSimpananController::class);
    Route::resource('pinjaman', PinjamanController::class);
    Route::resource('transaksi-pinjaman', TransaksiPinjamanController::class);
    Route::get('/pinjaman/{id}/cicilan-terakhir', [TransaksiPinjamanController::class, 'getCicilanTerakhir']);
    Route::resource('jurnal-kas-keluar', JurnalKasKeluarController::class)->only(['index']);
    Route::get('jurnal-kas-keluar/cetak', [JurnalKasKeluarController::class, 'cetakPdf'])->name('jurnal-kas-keluar.cetak');
    Route::resource('jurnal-kas-masuk', JurnalKasMasukController::class)->only(['index']);
    Route::get('jurnal-kas-masuk/cetak', [JurnalKasMasukController::class, 'cetakPdf'])->name('jurnal-kas-masuk.cetak');
    Route::get('/laporan/nisbah/tahun', [LaporanNisbahController::class, 'indexTahunan'])->name('laporan.nisbah.tahunan');
    Route::get('/laporan/nisbah/pdf/tahun', [LaporanNisbahController::class, 'exportPdfTahunan'])->name('laporan.nisbah.tahunan.pdf');
    Route::get('/laporan/nisbah/bulan', [LaporanNisbahController::class, 'indexBulanan'])->name('laporan.nisbah.bulanan');
    Route::get('/laporan/nisbah/pdf/bulan', [LaporanNisbahController::class, 'exportPdfBulanan'])->name('laporan.nisbah.bulanan.pdf');
});

Route::middleware(['auth', 'pimpinan'])->group(function () {
    Route::get('/user/dashboard', fn () => view('user.dashboard'));
});

require __DIR__.'/auth.php';
