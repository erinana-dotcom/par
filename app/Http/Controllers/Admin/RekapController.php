<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'tanggal_mulai'   => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ], [
            'tanggal_mulai.date'              => 'Format tanggal mulai tidak valid.',
            'tanggal_selesai.date'            => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal'  => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
        ]);

        // Default: bulan berjalan kalau belum ada filter
        $tanggalMulai   = $request->input('tanggal_mulai')   ?: now()->startOfMonth()->format('Y-m-d');
        $tanggalSelesai = $request->input('tanggal_selesai') ?: now()->format('Y-m-d');

        // Transaksi yang masuk ATAU keluar di dalam rentang tanggal
        $dalamRentang = function ($q) use ($tanggalMulai, $tanggalSelesai) {
            $q->where(function ($w) use ($tanggalMulai, $tanggalSelesai) {
                $w->whereDate('waktu_masuk', '>=', $tanggalMulai)
                  ->whereDate('waktu_masuk', '<=', $tanggalSelesai);
            })->orWhere(function ($w) use ($tanggalMulai, $tanggalSelesai) {
                $w->whereDate('waktu_keluar', '>=', $tanggalMulai)
                  ->whereDate('waktu_keluar', '<=', $tanggalSelesai);
            });
        };

        $totalTransaksi = Transaksi::where($dalamRentang)->count();
        $totalSelesai   = Transaksi::where($dalamRentang)->where('status', 'Keluar')->count();
        $totalTerparkir = Transaksi::where($dalamRentang)->where('status', 'Terparkir')->count();

        // Pendapatan dihitung pada hari kendaraan KELUAR (sama seperti dashboard)
        $totalPendapatan = (int) Transaksi::where('status', 'Keluar')
            ->whereDate('waktu_keluar', '>=', $tanggalMulai)
            ->whereDate('waktu_keluar', '<=', $tanggalSelesai)
            ->sum('total_biaya');

        $transaksis = Transaksi::where($dalamRentang)
            ->orderByDesc('waktu_masuk')
            ->paginate(25)
            ->withQueryString();

        return view('admin.rekap.index', compact(
            'transaksis', 'totalTransaksi', 'totalSelesai', 'totalTerparkir', 'totalPendapatan',
            'tanggalMulai', 'tanggalSelesai'
        ));
    }
}