<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DataBarang;
use App\Models\DetailPeminjaman;
use Illuminate\Support\Facades\DB;
use Alert;

class PeminjamanController extends Controller
{
    public function index()
    {
        // Mengambil data peminjaman beserta detail barang yang dipinjam
        $peminjaman = Peminjaman::with('detailPeminjaman.dataBarang')->paginate(10);
        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $databarang = DataBarang::all();
        return view('peminjaman.create', compact('databarang'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'lokasi_awal' => 'required|string|max:255',
            'lokasi_pinjam' => 'required|string|max:255',
            'ruangan' => 'required|string|max:255',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:databarang,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan ke tabel `peminjaman`
            $peminjaman = Peminjaman::create([
                'nama_peminjam' => $request->nama_peminjam,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'lokasi_awal' => $request->lokasi_awal,
                'lokasi_pinjam' => $request->lokasi_pinjam,
                'ruangan' => $request->ruangan,
                'status' => 'dipinjam',
            ]);

            // 2. Looping setiap barang yang dipilih
            foreach ($request->barang_id as $index => $barangId) {
                $barang = DataBarang::findOrFail($barangId);

                // Cek apakah stok cukup
                if ($barang->jumlah < $request->jumlah[$index]) {
                    return back()->with('error', 'Stok barang tidak mencukupi!')->withInput();
                }

                // Kurangi stok barang
                $barang->decrement('jumlah', $request->jumlah[$index]);

                // Simpan ke `detail_peminjaman`
                DetailPeminjaman::create([
                    'id_peminjaman' => $peminjaman->id,
                    'id_barang' => $barangId,
                    'jumlah' => $request->jumlah[$index],
                    'status_pengembalian' => 'belum',
                ]);
            }

            DB::commit();
            return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil ditambahkan.');
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
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'lokasi_awal' => 'required|string|max:255',
            'lokasi_pinjam' => 'required|string|max:255',
            'ruangan' => 'required|string|max:255',
            'status' => 'required|string|in:dipinjam,dikembalikan',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:databarang,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            // Update data detail peminjaman
            foreach ($request->barang_id as $key => $barang_id) {
                $databarang = DataBarang::findOrFail($barang_id);
                $detail = DetailPeminjaman::where('id_peminjaman', $peminjaman->id)->where('id_barang', $barang_id)->first();

                if ($detail) {
                    // Jika jumlah berubah, sesuaikan stok
                    $selisih = $request->jumlah[$key] - $detail->jumlah;
                    if ($selisih > 0 && $databarang->jumlah < $selisih) {
                        return back()->with('error', 'Jumlah barang yang dipinjam melebihi stok!')->withInput();
                    }

                    $databarang->decrement('jumlah', $selisih);
                    $detail->update(['jumlah' => $request->jumlah[$key]]);
                }
            }

            $peminjaman->update($request->all());

            DB::commit();
            Alert::success('Sukses!', 'Peminjaman berhasil diperbarui.');
            return redirect()->route('peminjaman.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Kembalikan stok barang sebelum menghapus
        $detailPeminjaman = DetailPeminjaman::where('id_peminjaman', $id)->get();
        foreach ($detailPeminjaman as $detail) {
            DataBarang::where('id', $detail->id_barang)->increment('jumlah', $detail->jumlah);
        }

        DetailPeminjaman::where('id_peminjaman', $id)->delete();
        $peminjaman->delete();

        Alert::success('Sukses!', 'Data peminjaman berhasil dihapus.');
        return redirect()->route('peminjaman.index');
    }
}
