<?php

namespace App\Http\Controllers;

use App\Models\LokasiToko;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;

class KunjunganController extends Controller
{

    public function index()
    {
        $toko = LokasiToko::all();
        return view('kunjungan.index', compact('toko'));
    }


    public function create()
    {
        return view('kunjungan.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'barcode'   => 'required|string|max:8|unique:lokasi_toko,barcode',
            'nama_toko' => 'required|string|max:50',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy'  => 'required|numeric|min:0',
        ]);

        LokasiToko::create([
            'barcode'    => $request->barcode,
            'nama_toko'  => $request->nama_toko,
            'latitude'   => $request->latitude,
            'longitude'  => $request->longitude,
            'accuracy'   => $request->accuracy,
            'created_at' => now(),
        ]);

        return redirect()->route('kunjungan.index')
                         ->with('success', 'Toko berhasil ditambahkan!');
    }


    public function destroy($barcode)
    {
        $toko = LokasiToko::findOrFail($barcode);
        $toko->delete();

        return redirect()->route('kunjungan.index')
                         ->with('success', 'Toko berhasil dihapus.');
    }


    public function scan()
    {
        return view('kunjungan.scan');
    }


    public function apiToko($barcode)
    {
        $toko = LokasiToko::where('barcode', $barcode)->first();

        if (!$toko) {
            return response()->json(['error' => 'Toko tidak ditemukan'], 404);
        }

        return response()->json($toko);
    }


    public function simpanKunjungan(Request $request)
    {
        $request->validate([
            'barcode_toko'   => 'required|string',
            'lat_sales'      => 'required|numeric',
            'lng_sales'      => 'required|numeric',
            'accuracy_sales' => 'required|numeric',
        ]);

        // Ambil data toko dari DB
        $toko = LokasiToko::where('barcode', $request->barcode_toko)->first();
        if (!$toko) {
            return response()->json(['error' => 'Toko tidak ditemukan'], 404);
        }

        // Hitung jarak dengan formula Haversine
        $jarak = $this->haversine(
            $toko->latitude, $toko->longitude,
            $request->lat_sales, $request->lng_sales
        );

        // Threshold efektif = threshold dasar + accuracy toko + accuracy sales
        $thresholdDasar   = 100; // meter — bisa disesuaikan
        $thresholdEfektif = $thresholdDasar + $toko->accuracy + $request->accuracy_sales;

        $status = ($jarak <= $thresholdEfektif) ? 'VALID' : 'DITOLAK';

        // Simpan ke tabel kunjungan
        Kunjungan::create([
            'barcode_toko'      => $request->barcode_toko,
            'lat_sales'         => $request->lat_sales,
            'lng_sales'         => $request->lng_sales,
            'accuracy_sales'    => $request->accuracy_sales,
            'jarak_aktual'      => round($jarak, 2),
            'threshold_efektif' => round($thresholdEfektif, 2),
            'status'            => $status,
            'created_at'        => now(),
        ]);

        return response()->json([
            'status'            => $status,
            'jarak_aktual'      => round($jarak, 2),
            'accuracy_toko'     => $toko->accuracy,
            'accuracy_sales'    => $request->accuracy_sales,
            'threshold_dasar'   => $thresholdDasar,
            'threshold_efektif' => round($thresholdEfektif, 2),
            'nama_toko'         => $toko->nama_toko,
            'barcode'           => $toko->barcode,
        ]);
    }


    public function cetakBarcode($barcode)
    {
        $toko = LokasiToko::findOrFail($barcode);

        $generator = new BarcodeGeneratorPNG();
        $png       = $generator->getBarcode($barcode, $generator::TYPE_CODE_128, 2, 60);
        $base64    = base64_encode($png);

        return view('kunjungan.barcode', compact('toko', 'base64'));
    }


    private function haversine($lat1, $lng1, $lat2, $lng2): float
    {
        $R    = 6371000; // radius bumi dalam meter
        $dLat = ($lat2 - $lat1) * M_PI / 180;
        $dLng = ($lng2 - $lng1) * M_PI / 180;

        $a = sin($dLat / 2) ** 2
           + cos($lat1 * M_PI / 180)
           * cos($lat2 * M_PI / 180)
           * sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $R * $c; // jarak dalam meter
    }
}