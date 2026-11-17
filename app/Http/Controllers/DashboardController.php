<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $role  = Auth::user()->role;
        $areas = AreaParkir::orderBy('nama_zona')->get();

        // Jumlah kendaraan yang sedang parkir per zona
        $terisiPerZona = Transaksi::where('status', 'Terparkir')
            ->select('nama_zona', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('nama_zona')
            ->pluck('jumlah', 'nama_zona')
            ->mapWithKeys(fn ($v, $k) => [strtolower($k) => $v]);

        $zonaOkupansi = $areas->map(function ($a) use ($terisiPerZona) {
            $isi    = $terisiPerZona[strtolower($a->nama_zona)] ?? 0;
            $persen = $a->total_slot > 0 ? min(round($isi / $a->total_slot * 100), 100) : 0;

            return [
                'nama'   => $a->nama_zona,
                'isi'    => $isi,
                'total'  => $a->total_slot,
                'persen' => $persen,
            ];
        });

        // Dijumlahkan dari data per zona, jadi selalu cocok dengan kartu zona
        $jumlahZona = $areas->count();
        $totalSlot  = $areas->sum('total_slot');
        $terisi     = $zonaOkupansi->sum('isi');
        $tersedia   = $zonaOkupansi->sum(fn ($z) => max($z['total'] - $z['isi'], 0));

        // Pendapatan hanya untuk admin dan owner
        $pendapatan       = null;
        $persenPendapatan = null;

        if (in_array($role, ['admin', 'owner'])) {
            $pendapatan = $this->pendapatanPada(today());
            $kemarin    = $this->pendapatanPada(today()->subDay());

            $persenPendapatan = $kemarin > 0
                ? round(($pendapatan - $kemarin) / $kemarin * 100)
                : null;
        }

        // 5 aktivitas parkir terakhir (dikirim ke view di bawah)
        $transaksiTerbaru = Transaksi::orderByDesc('id')->take(5)->get();

        return view('dashboard', compact(
            'role', 'totalSlot', 'jumlahZona', 'terisi', 'tersedia',
            'pendapatan', 'persenPendapatan', 'zonaOkupansi', 'transaksiTerbaru'
        ));
    }

    private function pendapatanPada($tanggal): int
    {
        return (int) Transaksi::where('status', 'Keluar')
            ->whereBetween('waktu_keluar', [$tanggal->copy()->startOfDay(), $tanggal->copy()->endOfDay()])
            ->sum('total_biaya');
    }
}