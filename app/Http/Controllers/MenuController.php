<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role == 'admin') {
            $menu = Menu::with('vendor')->get();
        } else {
            $menu = Menu::whereHas('vendor', function ($q) use ($user) {
                $q->where('iduser', $user->id);
            })->get();
        }

        return view('menu.index', compact('menu'));
    }

    public function create()
    {
        return view('menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required',
            'harga' => 'required|numeric'
        ]);

        $vendor = Auth::user()->vendor;

        Menu::create([
            'idvendor' => $vendor->idvendor, // ✅ penting
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga
        ]);

        return redirect('/menu')->with('success', 'Menu berhasil ditambahkan');
    }

    public function show($idmenu)
    {
        $menu = Menu::with('vendor')->findOrFail($idmenu);

        return view('menu.show', compact('menu'));
    }

    public function edit($idmenu)
    {
        $menu = Menu::findOrFail($idmenu);

        return view('menu.edit', compact('menu'));
    }

    public function update(Request $request, $idmenu)
    {
        $request->validate([
            'nama_menu' => 'required',
            'harga' => 'required|numeric'
        ]);

        $menu = Menu::findOrFail($idmenu);

        $menu->update([
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga
        ]);

        return redirect('/menu')->with('success', 'Menu berhasil diupdate');
    }

    public function destroy($idmenu)
    {
        $menu = Menu::findOrFail($idmenu);
        $menu->delete();

        return redirect('/menu')->with('success', 'Menu berhasil dihapus');
    }
}