<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DataBarang;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PeminjamanController extends Controller
{
    public function index()
    {
        // alert
        $title = 'Hapus Data?';
        $text = "Isi Data tidak dapat kembali";
        confirmDelete($title, $text);

        $peminjaman = Peminjaman::paginate(10);
        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $peminjaman = Peminjaman::with('dataBarang')->where('status', 'dipinjam')->get();
        $databarang = DataBarang::all();
        return view('peminjaman.create', compact('databarang', 'peminjaman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_databarang' => 'required|exists:databarang,id',
            'nama_peminjam' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'jenis_barang' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'lokasi_awal' => 'required|string|max:255',
            'lokasi_pinjam' => 'required|string|max:255',
            'ruangan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $databarang = DataBarang::findOrFail($request->id_databarang);

            if ($databarang->jumlah == 0) {
                return back()->with('error', 'Stok barang habis, tidak dapat dipinjam!')->withInput();
            }

            if ($databarang->jumlah < $request->jumlah) {
                return back()->with('error', 'Jumlah barang yang dipinjam melebihi stok yang tersedia!')->withInput();
            }

            // Kurangi stok barang
            $databarang->decrement('jumlah', $request->jumlah);

            // Simpan data peminjaman
            Peminjaman::create([
                'id_databarang' => $request->id_databarang,
                'nama_peminjam' => $request->nama_peminjam,
                'jumlah' => $request->jumlah,
                'jenis_barang' => $request->jenis_barang,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'lokasi_awal' => $request->lokasi_awal,
                'lokasi_pinjam' => $request->lokasi_pinjam,
                'ruangan' => $request->ruangan,
                'status' => 'Dipinjam',
            ]);

            DB::commit();

            Alert::success('Sukses!', 'Barang berhasil dipinjam dan jumlah barang diperbarui.');
            return redirect()->route('peminjaman.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())->withInput();
        }
    }



    public function edit($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        return view('peminjaman.edit', compact('peminjaman'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|string|max:255',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        if ($request->status === 'Dikembalikan' && $peminjaman->status !== 'Dikembalikan') {
            $barang = DataBarang::find($peminjaman->id_databarang);
            if ($barang) {
                $barang->stok += $peminjaman->jumlah;
                $barang->save();
            }
        }

        $peminjaman->update($request->all());

        Alert::success('Sukses', 'Peminjaman berhasil diperbarui!');
        return redirect()->route('peminjaman.index');
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $peminjaman = Peminjaman::findOrFail($id);
            $databarang = DataBarang::findOrFail($peminjaman->id_databarang);

            // Kembalikan stok barang
            $databarang->increment('jumlah', $peminjaman->jumlah);

            // Hapus data peminjaman
            $peminjaman->delete();

            DB::commit();

            Alert::success('Sukses!', 'Peminjaman berhasil dihapus dan stok barang dikembalikan.');
            return redirect()->route('peminjaman.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()); // ✅ Kurung diperbaiki
        }
    }



}
