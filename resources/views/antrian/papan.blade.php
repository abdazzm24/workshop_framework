<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Antrian</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: #0a0f1e;
            color: white;
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            overflow: hidden;
        }

        /* Header */
        .header {
            background: #3b82f6;
            padding: 16px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 1.6rem;
            font-weight: 800;
        }

        .header .jam {
            font-size: 1.4rem;
            font-weight: 300;
            opacity: 0.9;
        }

        /* Area nomor utama */
        .area-utama {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            min-height: 60vh;
        }

        .label-kecil {
            font-size: 1rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            opacity: 0.5;
            margin-bottom: 10px;
        }

        .nomor-besar {
            font-size: 15rem;
            font-weight: 900;
            line-height: 0.9;
            color: #3b82f6;
            transition: color 0.4s;
        }

        .nama-besar {
            font-size: 2.8rem;
            font-weight: 300;
            margin-top: 16px;
            text-align: center;
            opacity: 0.9;
        }

        .teks-tunggu {
            opacity: 0.35;
        }

        /* Area antrian bawah */
        .area-bawah {
            background: rgba(255,255,255,0.05);
            border-top: 1px solid rgba(255,255,255,0.1);
            padding: 18px 40px;
        }

        .area-bawah h3 {
            font-size: 0.85rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            opacity: 0.45;
            margin-bottom: 10px;
        }

        .list-antrian {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .chip {
            background: rgba(59,130,246,0.2);
            border: 1px solid rgba(59,130,246,0.4);
            border-radius: 8px;
            padding: 6px 16px;
            font-size: 1rem;
            font-weight: 600;
        }

        .chip span {
            opacity: 0.6;
            font-size: 0.82rem;
            margin-left: 6px;
            font-weight: 400;
        }

        /* Animasi flash saat nomor berubah */
        @keyframes flash {
            0%   { color: white; }
            50%  { color: #93c5fd; }
            100% { color: #3b82f6; }
        }

        .flash { animation: flash 0.8s ease; }
    </style>
</head>
<body>

<div class="header">
    <h1>Papan Antrian</h1>
    <div class="jam" id="jam">--:--:--</div>
</div>

<div class="area-utama">
    <div class="label-kecil">Nomor Dipanggil</div>
    <div id="nomor-besar" class="nomor-besar teks-tunggu">---</div>
    <div id="nama-besar" class="nama-besar teks-tunggu">Menunggu panggilan...</div>
</div>

<div class="area-bawah">
    <h3>Antrian Berikutnya</h3>
    <div id="list-antrian" class="list-antrian">
        <span style="opacity:0.35;">Belum ada antrian</span>
    </div>
</div>

{{-- Audio ting-tong — taruh file di public/sounds/dingdong.mp3 --}}
<audio id="audio-ding" src="{{ asset('sounds/dingdong.mp3') }}" preload="auto"></audio>

<script>
// ─── VARIABEL ─────────────────────────────────────────────────────────────────
let nomorSebelumnya = null;
let voiceIndonesia  = null; // akan diisi setelah voices loaded

// ─── JAM ──────────────────────────────────────────────────────────────────────
function updateJam() {
    const now = new Date();
    document.getElementById('jam').textContent =
        now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
}
setInterval(updateJam, 1000);
updateJam();

// ─── MUAT SUARA INDONESIA ─────────────────────────────────────────────────────
// Browser butuh waktu untuk load daftar voice — pakai event onvoiceschanged
function muatSuaraIndonesia() {
    const voices = window.speechSynthesis.getVoices();

    // Cari voice Indonesia — prioritas dari yang paling spesifik
    voiceIndonesia =
        voices.find(v => v.lang === 'id-ID' && v.localService) ||  // lokal Windows
        voices.find(v => v.lang === 'id-ID') ||                     // online fallback
        voices.find(v => v.lang.startsWith('id')) ||                // id-XX apapun
        null;

    if (voiceIndonesia) {
        console.log('Voice Indonesia ditemukan:', voiceIndonesia.name);
    } else {
        console.warn('Voice Indonesia tidak ditemukan. Pastikan sudah install di Settings Windows.');
    }
}

// Panggil saat pertama load
if (window.speechSynthesis.onvoiceschanged !== undefined) {
    window.speechSynthesis.onvoiceschanged = muatSuaraIndonesia;
}
// Panggil juga langsung untuk browser yang langsung ready
muatSuaraIndonesia();

// ─── SSE — Dengarkan update dari server ───────────────────────────────────────
const source = new EventSource('{{ route("antrian.stream") }}');

source.addEventListener('queue-update', function(e) {
    const data = JSON.parse(e.data);
    updatePapan(data);
});

source.onerror = function() {
    // Browser otomatis reconnect — tidak perlu handle
};

// ─── UPDATE TAMPILAN ──────────────────────────────────────────────────────────
function updatePapan(data) {
    const elNomor = document.getElementById('nomor-besar');
    const elNama  = document.getElementById('nama-besar');

    if (data.dipanggil) {
        const nomorBaru = data.dipanggil.nomor;
        const nomorPad  = String(nomorBaru).padStart(3, '0');

        // Hanya proses kalau nomor berubah
        if (nomorBaru !== nomorSebelumnya) {

            // Tampilkan nomor & nama
            elNomor.textContent = nomorPad;
            elNama.textContent  = data.dipanggil.nama;

            // Hapus class tunggu, tambah flash
            elNomor.classList.remove('teks-tunggu', 'flash');
            elNama.classList.remove('teks-tunggu');
            void elNomor.offsetWidth; // trick reset animasi CSS
            elNomor.classList.add('flash');

            // Bunyi panggilan (hanya kalau bukan pertama kali load)
            if (nomorSebelumnya !== null) {
                bunyiPanggilan(nomorPad, data.dipanggil.nama);
            }

            nomorSebelumnya = nomorBaru;
        }

    } else {
        // Tidak ada yang dipanggil
        elNomor.textContent = '---';
        elNama.textContent  = 'Menunggu panggilan...';
        elNomor.classList.add('teks-tunggu');
        elNama.classList.add('teks-tunggu');
    }

    // Update chip antrian bawah
    const elList = document.getElementById('list-antrian');
    if (data.menunggu.length === 0) {
        elList.innerHTML = '<span style="opacity:0.35;">Antrian kosong</span>';
    } else {
        elList.innerHTML = data.menunggu.slice(0, 12).map(item => {
            const nPad = String(item.nomor).padStart(3, '0');
            return `<div class="chip">${nPad}<span>${item.nama}</span></div>`;
        }).join('');
    }
}

// ─── BUNYI PANGGILAN ──────────────────────────────────────────────────────────
// Alur: audio ding → selesai → text-to-speech Indonesia
function bunyiPanggilan(nomorPad, nama) {
    // Batalkan speech yang mungkin masih jalan
    window.speechSynthesis.cancel();

    // Teks yang akan diucapkan
    // Nomor dipisah per digit agar dibaca satu per satu
    // Contoh: "003" → "nol nol tiga"
    const teks = `Nomor antrian ${bacaNomor(nomorPad)}, ${nama}, silakan masuk.`;

    const ucapan = new SpeechSynthesisUtterance(teks);
    ucapan.lang   = 'id-ID';
    ucapan.rate   = 0.9;   // sedikit lambat agar jelas
    ucapan.pitch  = 1.0;
    ucapan.volume = 1.0;

    // Pakai voice Indonesia kalau sudah dimuat
    if (voiceIndonesia) {
        ucapan.voice = voiceIndonesia;
    }

    // Putar audio ding dulu, baru ucapkan teks
    const audio = document.getElementById('audio-ding');
    audio.currentTime = 0;

    audio.play()
        .then(() => {
            audio.onended = function() {
                window.speechSynthesis.speak(ucapan);
            };
        })
        .catch(() => {
            // Kalau audio gagal (file tidak ada), langsung ucapkan saja
            window.speechSynthesis.speak(ucapan);
        });
}

// ─── BACA NOMOR PER DIGIT ─────────────────────────────────────────────────────
// Supaya "003" dibaca "nol nol tiga", bukan "tiga"
function bacaNomor(nomorPad) {
    const angka = {
        '0': 'nol', '1': 'satu', '2': 'dua', '3': 'tiga', '4': 'empat',
        '5': 'lima', '6': 'enam', '7': 'tujuh', '8': 'delapan', '9': 'sembilan'
    };
    return nomorPad.split('').map(d => angka[d]).join(', ');
}
</script>
</body>
</html>