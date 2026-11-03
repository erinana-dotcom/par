<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AreaParkir;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = AreaParkir::orderBy('id', 'desc')->get();
        return view('admin.area.index', compact('areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_zona'  => 'required|string|max:255|unique:area_parkir,nama_zona',
            'total_slot' => 'required|integer|min:1',
        ]);

        AreaParkir::create($request->only('nama_zona', 'total_slot'));

        return redirect()->route('area.index')->with('success', 'Area parkir berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $area = AreaParkir::findOrFail($id);

        $request->validate([
            'nama_zona'  => 'required|string|max:255|unique:area_parkir,nama_zona,' . $area->id,
            'total_slot' => 'required|integer|min:1',
        ]);

        $area->update($request->only('nama_zona', 'total_slot'));

        return redirect()->route('area.index')->with('success', 'Area parkir berhasil diperbarui!');
    }

    public function destroy($id)
    {
        AreaParkir::findOrFail($id)->delete();
        return redirect()->route('area.index')->with('success', 'Area parkir berhasil dihapus!');
    }
}