<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all();

        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric'
        ]);

        Barang::create([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'timestamp' => now() // penting karena NOT NULL
        ]);

        return redirect('/barang')->with('success', 'Barang berhasil ditambahkan');
    }

    public function show($id_barang)
    {
        $barang = Barang::findOrFail($id_barang);

        return view('barang.show', compact('barang'));
    }

    public function edit($id_barang)
    {
        $barang = Barang::findOrFail($id_barang);

        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, $id_barang)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric'
        ]);

        $barang = Barang::findOrFail($id_barang);

        $barang->update([
            'nama' => $request->nama,
            'harga' => $request->harga
        ]);

        return redirect('/barang')->with('success', 'Barang berhasil diupdate');
    }

    public function destroy($id_barang)
    {
        $barang = Barang::findOrFail($id_barang);
        $barang->delete();

        return redirect('/barang')->with('success', 'Barang berhasil dihapus');
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'barang_id' => 'required',
            'x' => 'required|integer|min:1|max:5',
            'y' => 'required|integer|min:1|max:8',
        ]);

        $barang = Barang::whereIn('id_barang', $request->barang_id)->get();

        $x = $request->x;
        $y = $request->y;

        $pdf = Pdf::loadView('barang.cetak', compact('barang','x','y'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream('label-barang.pdf');
    }
}