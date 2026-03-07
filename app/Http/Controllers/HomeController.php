<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Barang;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Total Buku
        $totalBuku = Buku::count();

        // Total Kategori
        $totalKategori = Kategori::count();

        // Buku Ditambahkan Bulan Ini
        $bukuBulanIni = Buku::whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->count();

        // Total Barang
        $totalBarang = Barang::count();

        // 5 Buku Terbaru
        $bukuTerbaru = Buku::with('kategori')
                            ->latest()
                            ->take(5)
                            ->get();

        // Kategori Populer (berdasarkan jumlah buku)
        $kategoriPopuler = Kategori::withCount('buku')
                                    ->orderBy('buku_count', 'desc')
                                    ->take(5)
                                    ->get();

        return view('home', compact(
            'totalBuku',
            'totalKategori',
            'bukuBulanIni',
            'totalBarang',
            'bukuTerbaru',
            'kategoriPopuler'
        ));
    }
}
