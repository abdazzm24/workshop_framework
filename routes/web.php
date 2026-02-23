<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
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

