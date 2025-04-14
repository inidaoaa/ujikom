<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\DataBarang;

class PembelianApiController extends Controller
{
    /**
     * Tampilkan semua data pembelian dengan relasi barang.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $pembelians = Pembelian::with('dataBarang')
            ->when($search, function ($query, $search) {
                return $query->whereHas('dataBarang', function ($subQuery) use ($search) {
                    $subQuery->where('nama_barang', 'like', "%{$search}%")
                             ->orWhere('jenis_barang', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'List data pembelian',
            'total' => $pembelians->count(),
            'data' => $pembelians
        ]);
    }

    /**
     * Simpan data pembelian baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_databarang'   => 'required|exists:databarang,id',
            'jenis_barang'    => 'required|string|max:255',
            'tahun_pembelian' => 'required|string|max:255',
            'harga'           => 'required|string|max:255',
            'keterangan'      => 'nullable|string|max:255',
            'jumlah'          => 'required|integer|min:1',
        ]);

        $pembelian = Pembelian::create($request->all());

        // Tambah jumlah barang
        DataBarang::where('id', $request->id_databarang)->increment('jumlah', $request->jumlah);

        return response()->json([
            'success' => true,
            'message' => 'Data pembelian berhasil disimpan',
            'data' => $pembelian
        ]);
    }

    /**
     * Tampilkan detail satu pembelian.
     */
    public function show($id)
    {
        $pembelian = Pembelian::with('dataBarang')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail pembelian',
            'data' => $pembelian
        ]);
    }

    /**
     * Perbarui data pembelian.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_databarang'   => 'required|exists:databarang,id',
            'jenis_barang'    => 'required|string|max:255',
            'tahun_pembelian' => 'required|string|max:255',
            'harga'           => 'required|string|max:255',
            'keterangan'      => 'nullable|string|max:255',
            'jumlah'          => 'required|integer|min:1',
        ]);

        $pembelian = Pembelian::findOrFail($id);
        $databarang_lama = DataBarang::findOrFail($pembelian->id_databarang);
        $databarang_lama->decrement('jumlah', $pembelian->jumlah);

        $pembelian->update($request->all());

        $databarang_baru = DataBarang::findOrFail($request->id_databarang);
        $databarang_baru->increment('jumlah', $request->jumlah);

        return response()->json([
            'success' => true,
            'message' => 'Data pembelian berhasil diperbarui',
            'data' => $pembelian
        ]);
    }

    /**
     * Hapus data pembelian.
     */
    public function destroy($id)
    {
        $pembelian = Pembelian::findOrFail($id);
        $databarang = $pembelian->dataBarang;

        $databarang->decrement('jumlah', $pembelian->jumlah);
        if ($databarang->jumlah < 0) {
            $databarang->update(['jumlah' => 0]);
        }

        $pembelian->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pembelian berhasil dihapus'
        ]);
    }
}
