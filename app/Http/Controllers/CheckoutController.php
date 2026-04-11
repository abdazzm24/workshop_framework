<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\DetailPesanan;


class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        // 🔥 AUTO NAMA
        $last = Pesanan::max('idpesanan') + 1;
        $last = $last ?? 1;
        $kode = str_pad($last, 4, '0', STR_PAD_LEFT);
        $namaCustomer = 'Guest_' . $kode;

        // 🔥 SIMPAN PESANAN
        $pesanan = Pesanan::create([
            'idcustomer' => $request->customer_id,
            'nama' => $namaCustomer,
            'total' => $request->total,
            'metode_bayar' => 'qris',
            'status_bayar' => 0
        ]);

        // 🔥 SIMPAN DETAIL
        foreach ($request->items as $item) {
            DetailPesanan::create([
                'idpesanan' => $pesanan->idpesanan,
                'idmenu' => $item['menu_id'],
                'jumlah' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['subtotal']
            ]);
        }

        return response()->json(['success' => true]);
    }
}