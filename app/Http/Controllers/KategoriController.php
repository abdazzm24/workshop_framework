<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();

        return view('kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required'
        ]);

        Kategori::create($request->all());

        return redirect('/kategori')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function show($idkategori)
    {
        $kategori = Kategori::findOrFail($idkategori);
        return view('kategori.show', compact('kategori'));
    }

    public function edit($idkategori)
    {
        $kategori = Kategori::findOrFail($idkategori);
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $idkategori)
    {
        $request->validate([
            'nama_kategori' => 'required'
        ]);

        $kategori = Kategori::findOrFail($idkategori);
        $kategori->update($request->all());

        return redirect('/kategori')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy($idkategori)
    {
        $kategori = Kategori::findOrFail($idkategori);
        $kategori->delete();

        return redirect('/kategori')->with('success', 'Kategori berhasil dihapus');
    }
}
