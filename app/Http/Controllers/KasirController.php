<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{

    public function bayar(Request $request)
    {

        $id_penjualan = date('YmdHis');

        DB::table('penjualan')->insert([
            'id_penjualan' => $id_penjualan,
            'timestamp' => now(),
            'total' => $request->total
        ]);

        foreach($request->detail as $item){

            DB::table('penjualan_detail')->insert([
                'id_penjualan' => $id_penjualan,
                'id_barang' => $item['kode_barang'], // ganti kode_barang → id_barang
                'jumlah' => $item['jumlah'],
                'subtotal' => $item['subtotal']
            ]);

        }

        return response()->json([
            'status' => 'success'
        ]);

    }

}

