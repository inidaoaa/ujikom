<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Databarang;

class DataBarangController extends Controller
{
    /**
     * Tampilkan daftar barang dengan pencarian.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $databarang = Databarang::withSum('pembelians', 'jumlah')
            ->when($search, function ($query, $search) {
                return $query->where('nama_barang', 'like', "%{$search}%");
            })->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar data barang',
            'data' => $databarang
        ]);
    }

    /**
     * Simpan data barang baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jenis_barang' => 'required|string|max:255',
            'merek' => 'required|string|max:255',
        ]);

        $barang = Databarang::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Barang berhasil ditambahkan.',
            'data' => $barang
        ], 201);
    }

    /**
     * Tampilkan detail barang tertentu.
     */
    public function show($id)
    {
        $barang = Databarang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $barang
        ]);
    }

    /**
     * Update data barang.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jenis_barang' => 'required|string|max:255',
            'merek' => 'required|string|max:255',
        ]);

        $barang = Databarang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang tidak ditemukan.'
            ], 404);
        }

        $barang->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Barang berhasil diperbarui.',
            'data' => $barang
        ]);
    }

    /**
     * Hapus barang dari database.
     */
    public function destroy($id)
    {
        $barang = Databarang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang tidak ditemukan.'
            ], 404);
        }

        $barang->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Barang berhasil dihapus.'
        ]);
    }
}
