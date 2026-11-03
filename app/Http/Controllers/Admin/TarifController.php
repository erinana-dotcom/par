<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TarifParkir;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    public function index()
    {
        $tarifs = TarifParkir::all();
        return view('admin.tarif.index', compact('tarifs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_kendaraan' => 'required|string|max:255',
            'tarif_per_jam' => 'required|numeric|min:0',
        ]);

        TarifParkir::create([
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tarif_per_jam' => $request->tarif_per_jam,
        ]);

        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        TarifParkir::findOrFail($id)->delete();
        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil dihapus!');
    }
}
