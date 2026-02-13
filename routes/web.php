<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/kategori', [BukuController::class, 'kategori'])->name('kategori');
Route::get('/buku', [BukuController::class, 'buku'])->name('buku');