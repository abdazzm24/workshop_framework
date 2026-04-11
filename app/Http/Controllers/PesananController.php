<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Customer;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    // 🔥 halaman list pesanan vendor
    public function index(Request $request)
    {
        $user = Auth::user();
        // ambil idvendor dari user login
        $idvendor = $user->idvendor;
        // filter status (optional)
        $status = $request->status;

        $query = Pesanan::query();

        // 🔥 filter berdasarkan vendor (lewat detail_pesanan → menu)
        $query->whereHas('detailPesanan.menu', function ($q) use ($idvendor) {
            $q->where('idvendor', $idvendor);
        });

        // 🔥 filter status bayar
        if ($status == 'lunas') {
            $query->where('status_bayar', 1);
        }

        $pesanan = $query->latest()->get();

        return view('vendor.pesanan', compact('pesanan'));
    }

    // 🔥 ubah status jadi lunas
    public function lunas($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $pesanan->update([
            'status_bayar' => 1
        ]);

        return back()->with('success', 'Pesanan sudah dibayar');
    }
    
    public function checkout(Request $request)
    {
        try {

            if (!$request->items || count($request->items) == 0) {
                return response()->json([
                    'error' => 'Keranjang kosong'
                ], 400);
            }

            if ($request->total <= 0) {
                return response()->json([
                    'error' => 'Total tidak valid'
                ], 400);
            }

            // 🔥 CONFIG MIDTRANS
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            // ambil customer dari request
            $customer = Customer::find($request->customer_id);

            // fallback kalau tidak pilih customer
            if ($customer) {
                $namaCustomer = $customer->nama;
                $customerId = $customer->id;
            } else {
                // fallback guest
                $last = Pesanan::max('idpesanan') ?? 0;
                $kode = str_pad($last + 1, 4, '0', STR_PAD_LEFT);
                $namaCustomer = 'Guest_' . $kode;
                $customerId = null;
            }

            // 🔥 SIMPAN PESANAN
            $pesanan = Pesanan::create([
                'nama' => $namaCustomer,
                'customer_id' => $customerId,
                'total' => (int) $request->total,
                'metode_bayar' => 'qris',
                'status_bayar' => 1
            ]);

            // 🔥 SIMPAN DETAIL
            foreach ($request->items as $item) {

                DetailPesanan::create([
                    'idpesanan' => $pesanan->idpesanan,
                    'idmenu' => $item['menu_id'],
                    'jumlah' => $item['qty'],
                    'harga' => (int) $item['harga'],
                    'subtotal' => (int) $item['subtotal']
                ]);
                
            }

            // 🔥 DATA MIDTRANS
            $params = [
                'transaction_details' => [
                    'order_id' => 'ORDER-' . $pesanan->idpesanan,
                    'gross_amount' => (int) $request->total,
                ],
                'customer_details' => [
                    'first_name' => $namaCustomer
                ]
            ];

            // 🔥 SNAP TOKEN
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $pesanan->idpesanan
            ]);
        
        } catch (\Exception $e) {

            \Log::error($e->getMessage());

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // 🔥 CALLBACK (SETELAH BAYAR)
    public function callback(Request $request)
    {
        $order_id = $request->order_id;

        // ambil id pesanan dari ORDER-1
        $id = str_replace('ORDER-', '', $order_id);

        Pesanan::where('idpesanan', $id)
            ->update(['status_bayar' => 1]);

        return response()->json(['success' => true]);
    }

    public function bayarSukses($id)
    {
        Pesanan::where('idpesanan', $id)
            ->update(['status_bayar' => 1]);

        return response()->json(['success' => true]);
    }

}