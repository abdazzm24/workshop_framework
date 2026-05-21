<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AntrianController extends Controller
{
    // ─── HALAMAN GUEST ────────────────────────────────────────────────────────

    public function guest()
    {
        return view('antrian.guest');
    }

    // ─── DAFTAR ANTRIAN (POST dari form guest) ────────────────────────────────

    public function daftar(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        // Ambil nomor terakhir hari ini, lalu +1
        $nomorTerakhir = Antrian::whereDate('created_at', today())
                                ->max('nomor');
        $nomorBaru = ($nomorTerakhir ?? 0) + 1;

        $antrian = Antrian::create([
            'nomor'      => $nomorBaru,
            'nama'       => $request->nama,
            'status'     => 'menunggu',
            'created_at' => now(),
        ]);

        // Update cache agar SSE langsung push ke admin
        $this->updateCache();

        // Redirect ke halaman tiket dengan nomor antrian
        return redirect()->route('antrian.tiket', $antrian->id);
    }

    // ─── HALAMAN TIKET (tab baru setelah daftar) ──────────────────────────────

    public function tiket($id)
    {
        $antrian = Antrian::findOrFail($id);
        return view('antrian.tiket', compact('antrian'));
    }

    // ─── HALAMAN ADMIN ────────────────────────────────────────────────────────

    public function admin()
    {
        $menunggu   = Antrian::whereDate('created_at', today())
                             ->where('status', 'menunggu')
                             ->orderBy('nomor')
                             ->get();

        $terlambat  = Antrian::whereDate('created_at', today())
                             ->where('status', 'terlambat')
                             ->orderBy('nomor')
                             ->get();

        $dipanggil  = Antrian::whereDate('created_at', today())
                             ->where('status', 'dipanggil')
                             ->first();

        return view('antrian.admin', compact('menunggu', 'terlambat', 'dipanggil'));
    }

    // ─── PANGGIL ANTRIAN BERIKUTNYA ───────────────────────────────────────────

    public function panggil(Request $request)
    {
        // Kalau ada yang masih 'dipanggil', tandai selesai dulu
        Antrian::whereDate('created_at', today())
               ->where('status', 'dipanggil')
               ->update(['status' => 'selesai']);

        // Ambil antrian berikutnya (nomor terkecil yang masih menunggu)
        $berikutnya = Antrian::whereDate('created_at', today())
                             ->where('status', 'menunggu')
                             ->orderBy('nomor')
                             ->first();

        if (!$berikutnya) {
            return response()->json(['error' => 'Tidak ada antrian yang menunggu'], 404);
        }

        $berikutnya->update([
            'status'    => 'dipanggil',
            'called_at' => now(),
        ]);

        $this->updateCache();

        return response()->json([
            'success' => true,
            'nomor'   => $berikutnya->nomor,
            'nama'    => $berikutnya->nama,
        ]);
    }

    // ─── PANGGIL ANTRIAN TERLAMBAT ────────────────────────────────────────────

    public function panggilTerlambat(Request $request)
    {
        $request->validate(['id' => 'required|integer']);

        // Kalau ada yang masih 'dipanggil', tandai selesai dulu
        Antrian::whereDate('created_at', today())
               ->where('status', 'dipanggil')
               ->update(['status' => 'selesai']);

        $antrian = Antrian::findOrFail($request->id);
        $antrian->update([
            'status'    => 'dipanggil',
            'called_at' => now(),
        ]);

        $this->updateCache();

        return response()->json([
            'success' => true,
            'nomor'   => $antrian->nomor,
            'nama'    => $antrian->nama,
        ]);
    }

    // ─── TANDAI TERLAMBAT ─────────────────────────────────────────────────────

    public function terlambat(Request $request)
    {
        $request->validate(['id' => 'required|integer']);

        $antrian = Antrian::findOrFail($request->id);
        $antrian->update(['status' => 'terlambat']);

        $this->updateCache();

        return response()->json(['success' => true]);
    }

    // ─── HALAMAN PAPAN ANTRIAN ────────────────────────────────────────────────

    public function papan()
    {
        return view('antrian.papan');
    }

    // ─── SSE STREAM ENDPOINT ──────────────────────────────────────────────────
    // Endpoint ini dibuka sekali oleh browser dan TIDAK PERNAH ditutup server
    // Server terus kirim data tiap 1 detik selama client masih terhubung

    public function stream()
    {
        return response()->stream(function () {
            // Cegah PHP timeout (koneksi SSE bisa berjam-jam)
            set_time_limit(0);

            $lastData = null; // simpan data terakhir agar tidak kirim ulang kalau sama

            while (true) {
                // Ambil state antrian terkini dari cache
                $data = Cache::get('antrian_state', $this->buildState());

                // Hanya kirim kalau ada perubahan (hemat bandwidth)
                $dataStr = json_encode($data);
                if ($dataStr !== $lastData) {
                    echo 'event: queue-update' . PHP_EOL;
                    echo 'data: ' . $dataStr . PHP_EOL;
                    echo PHP_EOL; // baris kosong = akhir pesan SSE

                    ob_flush();
                    flush();

                    $lastData = $dataStr;
                }

                // Kirim keep-alive komentar tiap loop agar koneksi tidak timeout
                echo ': keep-alive' . PHP_EOL;
                echo PHP_EOL;
                ob_flush();
                flush();

                // Cek apakah browser masih terhubung
                if (connection_aborted()) break;

                sleep(1); // cek update tiap 1 detik
            }
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no', // penting untuk Nginx
            'Connection'        => 'keep-alive',
        ]);
    }

    // ─── HELPER: BUILD STATE ──────────────────────────────────────────────────
    // Ambil state antrian terkini langsung dari DB (dipakai saat cache kosong)

    private function buildState(): array
    {
        $dipanggil = Antrian::whereDate('created_at', today())
                            ->where('status', 'dipanggil')
                            ->first();

        $menunggu  = Antrian::whereDate('created_at', today())
                            ->where('status', 'menunggu')
                            ->orderBy('nomor')
                            ->get(['id', 'nomor', 'nama']);

        $terlambat = Antrian::whereDate('created_at', today())
                            ->where('status', 'terlambat')
                            ->orderBy('nomor')
                            ->get(['id', 'nomor', 'nama']);

        return [
            'dipanggil'       => $dipanggil ? [
                'id'    => $dipanggil->id,
                'nomor' => $dipanggil->nomor,
                'nama'  => $dipanggil->nama,
            ] : null,
            'menunggu'        => $menunggu->toArray(),
            'terlambat'       => $terlambat->toArray(),
            'total_menunggu'  => $menunggu->count(),
            'updated_at'      => now()->toTimeString(),
        ];
    }

    // ─── HELPER: UPDATE CACHE ─────────────────────────────────────────────────
    // Dipanggil setiap kali ada perubahan data antrian
    // SSE stream akan mendeteksi perubahan cache dan push ke browser

    private function updateCache(): void
    {
        $state = $this->buildState();
        // Simpan 24 jam (akan diperbarui setiap ada aksi)
        Cache::put('antrian_state', $state, now()->addHours(24));
    }
}