<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::orderBy('id', 'desc')->get();
        return view('admin.kendaraan.index', compact('kendaraans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:20|unique:kendaraans,plat_nomor',
            'jenis_kendaraan' => 'required|string|max:255',
            'nama_pemilik' => 'nullable|string|max:255',
        ]);

        Kendaraan::create([
            'plat_nomor' => strtoupper($request->plat_nomor),
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'nama_pemilik' => $request->nama_pemilik,
        ]);

        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil didaftarkan!');
    }

    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $request->validate([
            'plat_nomor' => 'required|string|max:20|unique:kendaraans,plat_nomor,' . $kendaraan->id,
            'jenis_kendaraan' => 'required|string|max:255',
            'nama_pemilik' => 'nullable|string|max:255',
        ]);

        $kendaraan->update([
            'plat_nomor' => strtoupper($request->plat_nomor),
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'nama_pemilik' => $request->nama_pemilik,
        ]);

        return redirect()->route('kendaraan.index')->with('success', 'Data kendaraan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Kendaraan::findOrFail($id)->delete();
        return redirect()->route('kendaraan.index')->with('success', 'Data kendaraan berhasil dihapus!');
    }
}