<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\TarifParkir;
use App\Models\AreaParkir;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    // Batas biaya per hari = 10 kali tarif per jam (ubah angka ini sesuai kebijakan)
    private const BATAS_HARIAN_KALI_TARIF = 10;

    public function index()
    {
        $transaksis = Transaksi::orderByDesc('id')->paginate(20);
        $areas  = AreaParkir::all();
        $tarifs = TarifParkir::all();

        return view('admin.transaksi.index', compact('transaksis', 'areas', 'tarifs'));
    }

    public function store(Request $request)
    {
        // Normalisasi dulu sebelum divalidasi: huruf besar, tanpa spasi (B 1234 CD -> B1234CD)
        $platNomor = strtoupper(preg_replace('/\s+/', '', (string) $request->plat_nomor));
        $request->merge(['plat_nomor' => $platNomor]);

        $request->validate([
            'plat_nomor'      => ['required', 'regex:/^[A-Z0-9]{3,10}$/'],
            'jenis_kendaraan' => ['required', Rule::exists((new TarifParkir)->getTable(), 'jenis_kendaraan')],
            'nama_zona'       => ['required', Rule::exists((new AreaParkir)->getTable(), 'nama_zona')],
        ], [
            'plat_nomor.regex'       => 'Plat nomor hanya boleh huruf dan angka (3-10 karakter).',
            'jenis_kendaraan.exists' => 'Jenis kendaraan belum punya tarif. Tambahkan dulu di menu Tarif.',
            'nama_zona.exists'       => 'Zona parkir tidak valid.',
        ]);

        $hasil = DB::transaction(function () use ($request, $platNomor) {
            // Nama zona untuk pesan dan log: "zona a" -> "Zona A"
            $zonaTampil = ucwords($request->nama_zona);

            // Kunci baris zona supaya dua check-in bersamaan tidak melewati batas slot
            $area = AreaParkir::where('nama_zona', $request->nama_zona)->lockForUpdate()->first();

            $sedangParkir = Transaksi::where('plat_nomor', $platNomor)
                ->where('status', 'Terparkir')
                ->lockForUpdate()
                ->first();

            if ($sedangParkir) {
                return ['error' => "Kendaraan dengan plat {$platNomor} masih tercatat parkir dan belum keluar!"];
            }

            // Tolak check-in kalau zona sudah penuh
            $terisi = Transaksi::where('status', 'Terparkir')
                ->whereRaw('LOWER(nama_zona) = ?', [strtolower($request->nama_zona)])
                ->count();

            if ($area && $terisi >= $area->total_slot) {
                return ['error' => "{$zonaTampil} sudah penuh ({$terisi}/{$area->total_slot}). Pilih zona lain."];
            }

            Transaksi::create([
                'plat_nomor'      => $platNomor,
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'nama_zona'       => $request->nama_zona,
                'waktu_masuk'     => now(),
                'status'          => 'Terparkir',
            ]);

            $this->catatLog(
                'Check-In Kendaraan',
                "Kendaraan {$platNomor} ({$request->jenis_kendaraan}) masuk ke {$zonaTampil}"
            );

            return ['ok' => true];
        });

        if (isset($hasil['error'])) {
            return redirect()->route('transaksi.index')->with('error', $hasil['error']);
        }

        return redirect()->route('transaksi.index')->with('success', 'Kendaraan berhasil masuk!');
    }

    public function checkout($id)
    {
        $hasil = DB::transaction(function () use ($id) {
            // lockForUpdate: kalau diklik dua kali, request kedua menunggu lalu ditolak
            $transaksi = Transaksi::lockForUpdate()->findOrFail($id);

            if ($transaksi->status !== 'Terparkir') {
                return ['error' => 'Kendaraan ini sudah tercatat keluar.'];
            }

            $tarif = TarifParkir::where('jenis_kendaraan', $transaksi->jenis_kendaraan)->first();

            if (! $tarif) {
                return ['error' => "Tarif untuk {$transaksi->jenis_kendaraan} belum diatur. Tambahkan dulu di menu Tarif."];
            }

            $waktuMasuk  = Carbon::parse($transaksi->waktu_masuk);
            $waktuKeluar = now();

            // Durasi dibulatkan ke atas per jam, minimal 1 jam
            $detik  = max($waktuKeluar->timestamp - $waktuMasuk->timestamp, 0);
            $durasi = max((int) ceil($detik / 3600), 1);
            $total  = $this->hitungBiaya($durasi, (int) $tarif->tarif_per_jam);

            $transaksi->update([
                'waktu_keluar' => $waktuKeluar,
                'durasi_jam'   => $durasi,
                'total_biaya'  => $total,
                'status'       => 'Keluar',
            ]);

            $this->catatLog(
                'Check-Out Kendaraan',
                "Kendaraan {$transaksi->plat_nomor} keluar dari " . ucwords($transaksi->nama_zona)
                    . ", durasi {$durasi} jam, biaya Rp " . number_format($total, 0, ',', '.')
            );

            return ['total' => $total];
        });

        if (isset($hasil['error'])) {
            return redirect()->route('transaksi.index')->with('error', $hasil['error']);
        }

        return redirect()->route('transaksi.index')
            ->with('success', 'Kendaraan berhasil keluar! Total biaya: Rp ' . number_format($hasil['total'], 0, ',', '.'));
    }

    public function struk($id)
    {
        $transaksi = Transaksi::where('status', 'Keluar')->findOrFail($id);

        return view('admin.transaksi.struk', compact('transaksi'));
    }

    /**
     * Hitung biaya dengan batas maksimum per hari.
     * Contoh (tarif Rp 2.000/jam): 3 jam = 6.000 | 12 jam = 20.000 | 50 jam = 44.000
     */
    private function hitungBiaya(int $durasiJam, int $tarifPerJam): int
    {
        $batasHarian = $tarifPerJam * self::BATAS_HARIAN_KALI_TARIF;

        $jumlahHari = intdiv($durasiJam, 24); // hari penuh
        $sisaJam    = $durasiJam % 24;        // sisa jam di hari terakhir

        return ($jumlahHari * $batasHarian) + min($sisaJam * $tarifPerJam, $batasHarian);
    }

    private function catatLog(string $aktivitas, string $keterangan): void
    {
        ActivityLog::create([
            'user_id'    => auth()->id(),
            'nama_user'  => auth()->user()->name ?? 'Sistem',
            'aktivitas'  => $aktivitas,
            'keterangan' => $keterangan,
        ]);
    }
}