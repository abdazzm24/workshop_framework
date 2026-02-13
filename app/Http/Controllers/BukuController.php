<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    public function kategori()
    {
        $kategori = DB::table('kategori')->get();
        return view('kategori', compact('kategori'));
    }

    public function buku()
    {
        $buku = DB::table('buku')
            ->join('kategori', 'buku.idkategori', '=', 'kategori.idkategori')
            ->select('buku.*', 'kategori.nama_kategori')
            ->get();

        return view('buku', compact('buku'));
    }
}
