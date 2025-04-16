<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\DataBarang;
use Alert;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $title = 'Hapus Data?';
        $text = "Isi Data tidak dapat kembali";
        confirmDelete($title, $text);
        
        $search = $request->input('search');

        // Ambil data barang untuk modal Create
        $databarang = DataBarang::all();

        $pembelians = Pembelian::with('dataBarang')
            ->when($search, function ($query, $search) {
                return $query->whereHas('dataBarang', function ($subQuery) use ($search) {
                    $subQuery->where('nama_barang', 'like', "%{$search}%")
                        ->orWhere('jenis_barang', 'like', "%{$search}%");
                });
            })->paginate(10);

        return view('pembelian.index', compact('pembelians', 'databarang'));
    }

    /**
     * Store a newly created resource in storage.
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

        // Simpan data pembelian
        $pembelian = Pembelian::create([
            'id_databarang'   => $request->id_databarang,
            'jenis_barang'    => $request->jenis_barang,
            'tahun_pembelian' => $request->tahun_pembelian,
            'harga'           => $request->harga,
            'keterangan'      => $request->keterangan,
            'jumlah'          => $request->jumlah,
        ]);

        // Tambahkan jumlah barang
        DataBarang::where('id', $request->id_databarang)->increment('jumlah', $request->jumlah);

        Alert::success('Sukses!', 'Pembelian berhasil ditambahkan dan jumlah barang diperbarui.');
        return redirect()->route('pembelian.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pembelian  = Pembelian::findOrFail($id);
        $databarang = DataBarang::all();
        return view('pembelian.edit', compact('pembelian', 'databarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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

        // Kurangi jumlah barang lama sebelum update
        $databarang_lama->decrement('jumlah', $pembelian->jumlah);

        // Update pembelian
        $pembelian->update([
            'id_databarang'   => $request->id_databarang,
            'jenis_barang'    => $request->jenis_barang,
            'tahun_pembelian' => $request->tahun_pembelian,
            'harga'           => $request->harga,
            'keterangan'      => $request->keterangan,
            'jumlah'          => $request->jumlah,
        ]);

        // Tambahkan jumlah barang baru
        $databarang_baru = DataBarang::findOrFail($request->id_databarang);
        $databarang_baru->increment('jumlah', $request->jumlah);

        Alert::success('Sukses!', 'Pembelian berhasil diperbarui dan jumlah barang disesuaikan.');
        return redirect()->route('pembelian.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pembelian = Pembelian::findOrFail($id);
        $databarang = $pembelian->dataBarang;

        // Kurangi jumlah barang, tetapi tidak boleh negatif
        $databarang->decrement('jumlah', $pembelian->jumlah);
        if ($databarang->jumlah < 0) {
            $databarang->update(['jumlah' => 0]);
        }

        $pembelian->delete();

        Alert::success('Sukses!', 'Pembelian berhasil dihapus dan jumlah barang diperbarui.');
        return redirect()->route('pembelian.index');
    }
}
