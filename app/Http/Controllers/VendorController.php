<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Menu;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;


class VendorController extends Controller
{
    // =========================
    // 🔥 ADMIN VENDOR
    // =========================

    public function adminIndex()
    {
        $vendors = Vendor::with('user')->get();
        return view('adminvendor.index', compact('vendors'));
    }

    public function create()
    {
        $users = User::where('role', 'vendor')
                    ->whereNull('idvendor')
                    ->get();

        return view('adminvendor.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required',
            'user_id' => 'required|unique:vendor,user_id'
        ]);

        $vendor = Vendor::create([
            'nama_vendor' => $request->nama_vendor,
            'user_id' => $request->user_id
        ]);

        User::where('id', $request->user_id)
            ->update([
                'idvendor' => $vendor->idvendor
            ]);

        return redirect()->route('adminvendor.index')
            ->with('success', 'Vendor berhasil dibuat');
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('adminvendor.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_vendor' => 'required'
        ]);

        $vendor = Vendor::findOrFail($id);

        $vendor->update([
            'nama_vendor' => $request->nama_vendor
        ]);

        return redirect()->route('adminvendor.index')
            ->with('success', 'Vendor berhasil diupdate');
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);

        // kosongkan idvendor di user
        User::where('idvendor', $vendor->idvendor)
            ->update(['idvendor' => null]);

        $vendor->delete();

        return redirect()->route('adminvendor.index')
            ->with('success', 'Vendor berhasil dihapus');
    }

    public function adminPesanan($id)
    {
        $vendor = Vendor::findOrFail($id);

        $pesanan = \App\Models\Pesanan::with('detailPesanan.menu')
            ->latest()
            ->get();

        return view('adminvendor.pesanan', compact('vendor', 'pesanan'));
    }

    public function adminPesananDetail($id, $pesananId)
    {
        $vendor = Vendor::findOrFail($id);

        $pesanan = \App\Models\Pesanan::with('detailPesanan.menu')
            ->where('idpesanan', $pesananId)
            ->firstOrFail();

        return view('adminvendor.pesanan-detail', compact('vendor', 'pesanan'));
    }


    // =========================
    // 🔥 VENDOR (DASHBOARD)
    // =========================

    public function index()
    {
        $user = auth()->user();

        if ($user->idvendor == null) {
            return redirect('/home')->with('error', 'Belum punya vendor');
        }

        return view('vendor.index');
    }


    // =========================
    // 🔥 KELOLA MENU (SESUAI GAMBAR 1)
    // =========================

    public function menu()
    {
        $vendorId = Auth::user()->idvendor;

        $menus = Menu::where('idvendor', $vendorId)->get();

        return view('vendor.menu', compact('menus'));
    }

    public function storeMenu(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required',
            'harga' => 'required|numeric'
        ]);

        Menu::create([
            'idvendor' => Auth::user()->idvendor,
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga
        ]);

        return back()->with('success', 'Menu berhasil ditambahkan');
    }

    public function deleteMenu($id)
    {
        Menu::findOrFail($id)->delete();
        return back();
    }


    // =========================
    // 🔥 PESANAN (SESUAI GAMBAR 2)
    // =========================

    public function pesanan(Request $request)
    {
        $query = \App\Models\Pesanan::query();

        // filter lunas
        if ($request->status == 'lunas') {
            $query->where('status_bayar', 1);
        }

        $pesanan = $query->latest()->get();

        return view('vendor.pesanan', compact('pesanan'));
    }

    public function show($id)
    {
        $pesanan = \App\Models\Pesanan::with('detailPesanan.menu')
            ->where('idpesanan', $id)
            ->firstOrFail();

        return view('vendor.pesanan-detail', compact('pesanan'));
    }

    public function lunas($id)
    {
        $pesanan = \App\Models\Pesanan::findOrFail($id);
        $pesanan->status_bayar = 1;
        $pesanan->save();

        return redirect()->back()->with('success', 'Pesanan berhasil ditandai lunas');
    }

    public function struk($id)
    {
        $pesanan = \App\Models\Pesanan::with('detailPesanan.menu', 'customer')
            ->where('idpesanan', $id)
            ->firstOrFail();

        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode(
            $generator->getBarcode($pesanan->idpesanan, $generator::TYPE_CODE_128)
        );

        // 🔥 QR CODE (INI YANG DITAMBAHKAN)
        $qr = new QrCode($pesanan->idpesanan);

        $writer = new PngWriter();
        $result = $writer->write($qr);

        $qrcode = base64_encode($result->getString());

        $pdf = Pdf::loadView(
            'vendor.struk-pesanan',
            compact('pesanan', 'barcode', 'qrcode')
        );

        return $pdf->stream('struk-pesanan.pdf');
    }
}