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
    public function index()
    {
        $transaksis = Transaksi::orderByDesc('id')->paginate(20);
        $areas  = AreaParkir::all();
        $tarifs = TarifParkir::all();

        return view('admin.transaksi.index', compact('transaksis', 'areas', 'tarifs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plat_nomor'      => 'required|string|max:20',
            'jenis_kendaraan' => ['required', Rule::exists((new TarifParkir)->getTable(), 'jenis_kendaraan')],
            'nama_zona'       => ['required', Rule::exists((new AreaParkir)->getTable(), 'nama_zona')],
        ], [
            'jenis_kendaraan.exists' => 'Jenis kendaraan belum punya tarif. Tambahkan dulu di menu Tarif.',
            'nama_zona.exists'       => 'Zona parkir tidak valid.',
        ]);

        // Normalisasi: huruf besar, tanpa spasi (B 1234 CD -> B1234CD)
        $platNomor = strtoupper(preg_replace('/\s+/', '', $request->plat_nomor));

        $sedangParkir = Transaksi::where('plat_nomor', $platNomor)
            ->where('status', 'Terparkir')
            ->exists();

        if ($sedangParkir) {
            return redirect()->route('transaksi.index')
                ->with('error', "Kendaraan dengan plat {$platNomor} masih tercatat parkir dan belum keluar!");
        }

        Transaksi::create([
            'plat_nomor'      => $platNomor,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'nama_zona'       => $request->nama_zona,
            'waktu_masuk'     => now(),
            'status'          => 'Terparkir',
        ]);

        ActivityLog::create([
            'user_id'    => auth()->id(),
            'nama_user'  => auth()->user()->name ?? 'Sistem',
            'aktivitas'  => 'Check-In Kendaraan',
            'keterangan' => "Kendaraan {$platNomor} ({$request->jenis_kendaraan}) masuk ke {$request->nama_zona}",
        ]);

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
            $total  = $durasi * (int) $tarif->tarif_per_jam;

            $transaksi->update([
                'waktu_keluar' => $waktuKeluar,
                'durasi_jam'   => $durasi,
                'total_biaya'  => $total,
                'status'       => 'Keluar',
            ]);

            ActivityLog::create([
                'user_id'    => auth()->id(),
                'nama_user'  => auth()->user()->name ?? 'Sistem',
                'aktivitas'  => 'Check-Out Kendaraan',
                'keterangan' => "Kendaraan {$transaksi->plat_nomor} keluar, durasi {$durasi} jam, biaya Rp " . number_format($total, 0, ',', '.'),
            ]);

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
}