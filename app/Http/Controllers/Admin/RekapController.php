<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        // Default: tampilkan bulan berjalan kalau belum ada filter
        $tanggalMulai = $request->tanggal_mulai ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $tanggalSelesai = $request->tanggal_selesai ?? Carbon::now()->format('Y-m-d');

        $transaksis = Transaksi::whereDate('waktu_masuk', '>=', $tanggalMulai)
            ->whereDate('waktu_masuk', '<=', $tanggalSelesai)
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        $totalTransaksi = $transaksis->count();
        $totalSelesai = $transaksis->where('status', 'Keluar')->count();
        $totalTerparkir = $transaksis->where('status', 'Terparkir')->count();
        $totalPendapatan = $transaksis->sum('total_biaya');

        return view('admin.rekap.index', compact(
            'transaksis', 'totalTransaksi', 'totalSelesai', 'totalTerparkir', 'totalPendapatan',
            'tanggalMulai', 'tanggalSelesai'
        ));
    }
}