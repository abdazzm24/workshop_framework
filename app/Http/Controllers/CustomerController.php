<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customer.index', compact('customers'));
    }

    // ========================
    // 🔥 BLOB
    // ========================

    public function createBlob()
    {
        return view('customer.create-blob');
    }

    public function storeBlob(Request $request)
    {
        try {
            $image = $request->foto;

            // bersihkan base64
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            // simpan ke database (BLOB)
            $customer = Customer::create([
                'nama' => $request->nama,
                'foto_blob' => $request->foto
            ]);

            return response()->json(['id' => $customer->id]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ========================
    // 🔥 FILE
    // ========================

    public function createFile()
    {
        return view('customer.create-file');
    }

    public function storeFile(Request $request)
    {
        $image = $request->foto;

        $image = str_replace('data:image/png;base64,', '', $image);
        $image = base64_decode($image);

        $filename = 'customer_' . time() . '.png';
        Storage::disk('public')->put($filename, $image);

        Customer::create([
            'nama' => $request->nama,
            'foto_path' => $filename
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil disimpan (FILE)');
    }
}