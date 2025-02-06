<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Databarang;
use RealRashid\SweetAlert\Facades\Alert;

class DataBarangController extends Controller
{
    /**
     * Tampilkan daftar barang dengan pencarian.
     */
    public function index(Request $request)
    {
        $title = 'Ingin Menghapusnya?';
        $text = "Ini akan terhapus di semua table";
        confirmDelete($title, $text);

        $search = $request->input('search');

        $databarang = Databarang::withSum('pembelians', 'jumlah')
            ->when($search, function ($query, $search) {
                return $query->where('nama_barang', 'like', "%{$search}%");
            })->paginate(10);

        // Menyertakan parameter pencarian dalam pagination
        return view('databarang.index', compact('databarang'));
    }

    /**
     * Tampilkan form tambah barang.
     */
    public function create()
    {
        return view('databarang.create');
    }

    /**
     * Simpan data barang baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jenis_barang' => 'required|string|max:255',
            'merek' => 'required|string|max:255',
        ]);

        Databarang::create($request->all());

        Alert::success('Berhasil!', 'Barang berhasil ditambahkan.');
        return redirect()->route('databarang.index');
    }

    /**
     * Tampilkan form edit barang.
     */
    public function edit($id)
    {
        $databarang = Databarang::findOrFail($id);
        return view('databarang.edit', compact('databarang'));
    }

    /**
     * Update data barang.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jenis_barang' => 'required|string|max:255',
            'merek' => 'required|string|max:255',
        ]);

        $barang = Databarang::findOrFail($id);
        $barang->update($request->all());

        Alert::success('Berhasil!', 'Barang berhasil diperbarui.');
        return redirect()->route('databarang.index');
    }

    /**
     * Hapus barang dari database.
     */
    public function destroy($id)
{
    $barang = Databarang::findOrFail($id);
    $barang->delete();

    Alert::success('Berhasil!', 'Barang berhasil dihapus.');
    return redirect()->route('databarang.index');
}

}
