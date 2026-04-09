<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PesananController;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Menu;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', function () {return view('welcome');})->name('welcome');


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

// ajax wilayah
Route::get('/ajax-wilayah', function () {return view('ajax.wilayah');})->name('ajax.wilayah');

// ajax kasir
Route::get('/ajax-kasir', function () {return view('ajax.kasir');})->name('ajax.kasir');

// axios wilayah
Route::get('/axios-wilayah', function () {return view('axios.wilayah');})->name('axios.wilayah');

// axios kasir
Route::get('/axios-kasir', function () {return view('axios.kasir');})->name('axios.kasir');

// API Wilayah

// provinsi
Route::get('/api/provinsi', function () {$response = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');return $response->json();});

// kota
Route::get('/api/kota/{id}', function ($id) {$response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/$id.json");return $response->json();});

// kecamatan
Route::get('/api/kecamatan/{id}', function ($id) {$response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/districts/$id.json");return $response->json();});

// kelurahan
Route::get('/api/kelurahan/{id}', function ($id) {$response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/villages/$id.json");return $response->json();});

// api barang
Route::get('/api/barang', function () {
    $barang = DB::table('barang')->get();
    return response()->json($barang);

});

// bayar kasir
Route::post('/kasir/bayar',[KasirController::class,'bayar']);

// halaman vendor
Route::get('/vendor', [VendorController::class, 'index'])->name('vendor.index');

Route::post('/logout', function () {
    Auth::logout(); return redirect('/');})->name('logout');


// guest
// ambil vendor
Route::get('/api/vendor', function () {return \App\Models\Vendor::all();});
    
// ambil menu berdasarkan vendor
Route::get('/api/menu/{idvendor}', function ($idvendor) {return \App\Models\Menu::where('idvendor', $idvendor)->get();});
    
Route::get('/', function () {$vendors = \App\Models\Vendor::all();return view('welcome', compact('vendors'));})->name('welcome'); 
    
// ADMIN VENDOR (khusus admin)
Route::get('/adminvendor', [VendorController::class, 'adminIndex'])->name('adminvendor.index');
Route::get('/adminvendor/create', [VendorController::class, 'create'])->name('adminvendor.create');
Route::post('/adminvendor', [VendorController::class, 'store'])->name('adminvendor.store');
Route::get('/adminvendor/{id}/edit', [VendorController::class, 'edit'])->name('adminvendor.edit');
Route::put('/adminvendor/{id}', [VendorController::class, 'update'])->name('adminvendor.update');
Route::delete('/adminvendor/{id}', [VendorController::class, 'destroy'])->name('adminvendor.destroy');

// 🔥 VENDOR MENU
Route::get('/vendor/menu', [VendorController::class, 'menu'])->name('vendor.menu');
Route::post('/vendor/menu', [VendorController::class, 'storeMenu'])->name('vendor.menu.store');
Route::put('/vendor/menu/{id}', [VendorController::class, 'updateMenu'])->name('vendor.menu.update');
Route::delete('/vendor/menu/{id}', [VendorController::class, 'deleteMenu'])->name('vendor.menu.delete');

// 🔥 VENDOR PESANAN
Route::get('/vendor/pesanan', [VendorController::class, 'pesanan'])->name('vendor.pesanan');
Route::post('/vendor/pesanan/{id}/lunas', [VendorController::class, 'lunas'])->name('vendor.pesanan.lunas');

// Route::post('/checkout', [PesananController::class, 'store']);

Route::get('/vendor/pesanan', [PesananController::class, 'index'])->name('vendor.pesanan');
Route::post('/vendor/pesanan/{id}/lunas', [PesananController::class, 'lunas'])->name('vendor.pesanan.lunas');

// menu
Route::get('/get-menu/{idvendor}', function ($idvendor) {
    // ambil menu berdasarkan vendor
    $menu = Menu::where('idvendor', $idvendor)->get();
    return response()->json($menu);
});

Route::post('/checkout', [PesananController::class, 'checkout']);
Route::post('/midtrans/callback', [PesananController::class, 'callback']);
Route::post('/bayar-sukses/{id}', [PesananController::class, 'bayarSukses']);