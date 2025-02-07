<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DataBarang;
use Illuminate\Support\Facades\DB;
use Alert;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with('dataBarang')->paginate(10);
        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $databarang = DataBarang::all();
        return view('peminjaman.create', compact('databarang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'jenis_barang' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'lokasi_awal' => 'required|string|max:255',
            'lokasi_pinjam' => 'required|string|max:255',
            'ruangan' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'status' => 'required|string|in:dipinjam,dikembalikan',
            'id_databarang' => 'required|exists:databarang,id',
        ]);

        DB::beginTransaction();

        try {
            $databarang = DataBarang::findOrFail($request->id_databarang);

            if ($databarang->jumlah < $request->jumlah) {
                return back()->with('error', 'Jumlah barang yang dipinjam melebihi stok!')->withInput();
            }

            $databarang->decrement('jumlah', $request->jumlah);

            Peminjaman::create($request->all());

            DB::commit();

            Alert::success('Sukses!', 'Peminjaman berhasil ditambahkan.');
            return redirect()->route('peminjaman.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $databarang = DataBarang::all();
        return view('peminjaman.edit', compact('peminjaman', 'databarang'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'jenis_barang' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'lokasi_awal' => 'required|string|max:255',
            'lokasi_pinjam' => 'required|string|max:255',
            'ruangan' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'status' => 'required|string|in:dipinjam,dikembalikan',
            'id_databarang' => 'required|exists:databarang,id',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        $databarang = DataBarang::findOrFail($request->id_databarang);

        if ($peminjaman->jumlah != $request->jumlah) {
            $selisih = $request->jumlah - $peminjaman->jumlah;
            if ($selisih > 0 && $databarang->jumlah < $selisih) {
                return back()->with('error', 'Jumlah barang yang dipinjam melebihi stok!')->withInput();
            }
            $databarang->decrement('jumlah', $selisih);
        }

        $peminjaman->update($request->all());

        Alert::success('Sukses!', 'Peminjaman berhasil diperbarui.');
        return redirect()->route('peminjaman.index');
    }

    public function destroy(string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $databarang = DataBarang::findOrFail($peminjaman->id_databarang);

        $databarang->increment('jumlah', $peminjaman->jumlah);
        $peminjaman->delete();

        Alert::success('Sukses!', 'Data peminjaman berhasil dihapus.');
        return redirect()->route('peminjaman.index');
    }
}
