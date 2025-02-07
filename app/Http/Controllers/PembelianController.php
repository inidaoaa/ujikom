<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\DataBarang;
use Alert; // Import SweetAlert

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Ingin Menghapusnya?';
        $text = "Ketika di hapus tidak dapat kembali";

        $search = $request->input('search');

        $pembelians = Pembelian::with('dataBarang')
            ->when($search, function ($query, $search) {
                return $query->where('nama_barang', 'like', "%{$search}%")
                ->orWhere('jenis_barang', 'like', "%{$search}%");
            })->paginate(10);

        return view('pembelian.index', compact('pembelians'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $databarang = DataBarang::all();
        return view('pembelian.create', compact('databarang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_barang' => 'required|string|max:255',
            'tahun_pembelian' => 'required|string|max:255',
            'harga' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'id_databarang' => 'required|exists:databarang,id',
        ]);

        // Simpan data pembelian
        $pembelian = Pembelian::create($request->all());

        // Temukan Databarang yang sedang dibeli
        $databarang = DataBarang::findOrFail($request->id_databarang);

        // Tambahkan jumlah barang berdasarkan jumlah yang dibeli
        $databarang->increment('jumlah', $request->jumlah);

        // Tampilkan pesan sukses dan alihkan kembali
        Alert::success('Sukses!', 'Pembelian berhasil ditambahkan dan jumlah barang diperbarui.');
        return redirect()->route('pembelian.index');
    }






    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $pembelian = Pembelian::with('dataBarang')->findOrFail($id);
        // return view('pembelian.show', compact('pembelian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pembelian = Pembelian::findOrFail($id);
        $databarang = DataBarang::all();
        return view('pembelian.edit', compact('pembelian', 'databarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'jenis_barang' => 'required|string|max:255',
            'tahun_pembelian' => 'required|string|max:255',
            'harga' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'id_databarang' => 'required|exists:databarang,id',
        ]);

        // Ambil data pembelian sebelum diupdate
        $pembelian = Pembelian::findOrFail($id);
        $databarang = DataBarang::findOrFail($pembelian->id_databarang);

        // Kurangi stok barang lama sebelum update
        $databarang->decrement('jumlah', $pembelian->jumlah);

        // Perbarui data pembelian dengan data baru
        $pembelian->update($request->all());

        // Ambil data barang baru setelah update
        $databarang = DataBarang::findOrFail($request->id_databarang);

        // Tambahkan stok barang baru sesuai jumlah yang diperbarui
        $databarang->increment('jumlah', $request->jumlah);

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
