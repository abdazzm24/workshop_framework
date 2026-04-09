<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Menu;
use App\Models\Penjualan;
use Illuminate\Support\Facades\Auth;

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
}