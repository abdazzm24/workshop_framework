<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Buku;
use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// KATEGORI ROUTES
Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');

Route::get('/kategori/{idkategori}', [KategoriController::class, 'show'])->name('kategori.show');
Route::get('/kategori/{idkategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
Route::put('/kategori/{idkategori}', [KategoriController::class, 'update'])->name('kategori.update');
Route::delete('/kategori/{idkategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

// BUKU ROUTES
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');

Route::get('/buku/{idbuku}', [BukuController::class, 'show'])->name('buku.show');
Route::get('/buku/{idbuku}/edit', [BukuController::class, 'edit'])->name('buku.edit');
Route::put('/buku/{idbuku}', [BukuController::class, 'update'])->name('buku.update');
Route::delete('/buku/{idbuku}', [BukuController::class, 'destroy'])->name('buku.destroy');

// BARANG ROUTES
Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');

Route::post('/barang/cetak', [BarangController::class, 'cetak'])->name('barang.cetak');

Route::get('/barang/{idbarang}', [BarangController::class, 'show'])->name('barang.show');
Route::get('/barang/{idbarang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
Route::put('/barang/{idbarang}', [BarangController::class, 'update'])->name('barang.update');
Route::delete('/barang/{idbarang}', [BarangController::class, 'destroy'])->name('barang.destroy');


// Google Authentication Routes
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('callback');

// OTP Routes
Route::get('/otp', [LoginController::class, 'showOTP'])->name('otp.form');
Route::post('/otp', [LoginController::class, 'verifyOTP'])->name('otp.verify');

// Generate PDF Sertifikat
Route::get('/buku/{idbuku}/sertifikat', function ($id) {
    $buku = Buku::with('kategori')->findOrFail($id);

    $pdf = Pdf::loadView('pdf.sertifikat', compact('buku'))
              ->setPaper('a4', 'landscape');

    return $pdf->download('sertifikat-buku.pdf');
});

// laporan semua buku
Route::get('/buku/laporan/pdf', function () {
    $buku = Buku::with('kategori')->get();

    $pdf = Pdf::loadView('pdf.laporan_buku', compact('buku'))
              ->setPaper('a4', 'portrait');

    return $pdf->download('laporan-buku.pdf');
});

// laporan semua kategori
Route::get('/kategori/laporan/pdf', function () {
    $kategori = Kategori::all();

    $pdf = Pdf::loadView('pdf.laporan_kategori', compact('kategori'))
              ->setPaper('a4', 'portrait');

    return $pdf->download('laporan-kategori.pdf');
});

// sidebar table
Route::get('/tabel', function () {return view('tabel.index');})->name('tabel.index');

// sidebar datatables
Route::get('/datatables', function () {return view('datatables.index');})->name('datatables.index');

// sidebar select
Route::get('/select', function () {return view('select.index');})->name('select.index');

// sidebar select2
Route::get('/select2', function () {return view('select2.index');})->name('select2.index');