<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')->get();

        return view('buku.index', compact('buku'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('buku.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idkategori' => 'required',
            'kode' => 'required',
            'judul' => 'required',
            'pengarang' => 'required'
        ]);

        Buku::create($request->all());

        return redirect('/buku')->with('success', 'Buku berhasil ditambahkan');
    }

    public function show($idbuku)
    {
        $buku = Buku::with('kategori')->findOrFail($idbuku);
        return view('buku.show', compact('buku'));
    }

    public function edit($idbuku)
    {
        $buku = Buku::findOrFail($idbuku);
        $kategori = Kategori::all();

        return view('buku.edit', compact('buku', 'kategori'));
    }

    public function update(Request $request, $idbuku)
    {
        $request->validate([
            'idkategori' => 'required',
            'kode' => 'required',
            'judul' => 'required',
            'pengarang' => 'required'
        ]);

        $buku = Buku::findOrFail($idbuku);
        $buku->update($request->all());

        return redirect('/buku')->with('success', 'Buku berhasil diupdate');
    }

    public function destroy($idbuku)
    {
        $buku = Buku::findOrFail($idbuku);
        $buku->delete();

        return redirect('/buku')->with('success', 'Buku berhasil dihapus');
    }
}
