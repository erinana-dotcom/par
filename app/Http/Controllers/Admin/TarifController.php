<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TarifParkir;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TarifController extends Controller
{
    public function index()
    {
        $tarifs = TarifParkir::all();
        return view('admin.tarif.index', compact('tarifs'));
    }

    public function store(Request $request)
    {
        $table = (new TarifParkir)->getTable();

        $request->validate([
            'jenis_kendaraan' => ['required', 'string', 'max:255', Rule::unique($table, 'jenis_kendaraan')],
            'tarif_per_jam'   => 'required|numeric|min:0',
        ], [
            'jenis_kendaraan.unique' => 'Tarif untuk jenis kendaraan ini sudah ada. Gunakan tombol edit.',
        ]);

        TarifParkir::create([
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tarif_per_jam'   => $request->tarif_per_jam,
        ]);

        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $tarif = TarifParkir::findOrFail($id);
        $table = $tarif->getTable();

        $request->validate([
            'jenis_kendaraan' => ['required', 'string', 'max:255', Rule::unique($table, 'jenis_kendaraan')->ignore($tarif->id)],
            'tarif_per_jam'   => 'required|numeric|min:0',
        ], [
            'jenis_kendaraan.unique' => 'Tarif untuk jenis kendaraan ini sudah ada.',
        ]);

        $tarif->update([
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tarif_per_jam'   => $request->tarif_per_jam,
        ]);

        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil diperbarui!');
    }

    public function destroy($id)
    {
        TarifParkir::findOrFail($id)->delete();
        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil dihapus!');
    }
}