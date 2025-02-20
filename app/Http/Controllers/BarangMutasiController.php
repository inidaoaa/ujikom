<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMutasi;
use App\Models\DataBarang;
use RealRashid\SweetAlert\Facades\Alert;

class BarangMutasiController extends Controller
{
    /**
     * Tampilkan daftar barang mutasi dengan pencarian.
     */
    public function index(Request $request)
    {
        // alert
        $title = 'Hapus Data?';
        $text = "Isi Data tidak dapat kembali";
        confirmDelete($title, $text);
        //
        $search = $request->input('search');

        $barangMutasi = BarangMutasi::with('dataBarang')
            ->when($search, function ($query, $search) {
             return $query->where('jenis_barang', 'like', "%{$search}%")
             ->orWhere('lokasi_mutasi', 'like', "%{$search}%");
            })->paginate(10);

        return view('barangmutasi.index', compact('barangMutasi'));
    }

    /**
     * Tampilkan form tambah barang mutasi.
     */
    public function create()
    {
        $databarang = DataBarang::all();
        return view('barangmutasi.create', compact('databarang'));
    }

    /**
     * Simpan data barang mutasi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_barang' => 'required|string|max:255',
            'tanggal_mutasi' => 'required|date',
            'lokasi_awal' => 'required|string|max:255',
            'lokasi_mutasi' => 'required|string|max:255',
            'ruangan' => 'required|string|max:255',
            'lantai' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'id_databarang' => 'required|exists:databarang,id',
        ]);

        BarangMutasi::create($request->all());

        Alert::success('Berhasil!', 'Mutasi barang berhasil ditambahkan.');
        return redirect()->route('barangmutasi.index');
    }

    /**
     * Tampilkan form edit barang mutasi.
     */
    public function edit($id)
    {
        $barangMutasi = BarangMutasi::findOrFail($id);
        $databarang = DataBarang::all();
        return view('barangmutasi.edit', compact('barangMutasi', 'databarang'));
    }

    /**
     * Update data barang mutasi.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_barang' => 'required|string|max:255',
            'tanggal_mutasi' => 'required|date',
            'lokasi_awal' => 'required|string|max:255',
            'lokasi_mutasi' => 'required|string|max:255',
            'ruangan' => 'required|string|max:255',
            'lantai' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'id_databarang' => 'required|exists:databarang,id',
        ]);

        $barangMutasi = BarangMutasi::findOrFail($id);
        $barangMutasi->update($request->all());

        Alert::success('Berhasil!', 'Mutasi barang berhasil diperbarui.');
        return redirect()->route('barangmutasi.index');
    }

    /**
     * Hapus barang mutasi dari database.
     */
    public function destroy($id)
    {
        $barangMutasi = BarangMutasi::findOrFail($id);
        $barangMutasi->delete();

        Alert::success('Berhasil!', 'Mutasi barang berhasil dihapus.');
        return redirect()->route('barangmutasi.index');
    }
}
