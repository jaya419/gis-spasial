<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lokasi;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::all();
        return view('wilayah.index', compact('lokasi'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'deskripsi' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
        
        // Kalau deskripsi kosong, isi default
        if (empty($data['deskripsi'])) {
            $data['deskripsi'] = 'No Deskripsi';
        }
        
        Lokasi::create($data);        

        return response()->json(['message' => 'Lokasi berhasil ditambahkan']);
    }

    public function update(Request $request, $id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->update($request->all());

        return response()->json(['message' => 'Lokasi berhasil diupdate']);
    }

    public function destroy($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->delete();

        return response()->json(['message' => 'Lokasi berhasil dihapus']);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $lokasi = Lokasi::where('nama', 'like', "%$keyword%")
                         ->orWhere('deskripsi', 'like', "%$keyword%")
                         ->get();

        return response()->json($lokasi);
    }

    public function daftarLokasi()
{
    $lokasi = Lokasi::all();
    return view('wilayah.daftar', compact('lokasi'));
}

}
