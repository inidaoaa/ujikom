<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DataBarang;
use RealRashid\SweetAlert\Facades\Alert;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::paginate(10);
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
            'id_databarang' => 'required|exists:databarang,id',
            'jumlah' => 'required|integer|min:1',
            'jenis_barang' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'lokasi_awal' => 'required|string|max:255',
            'lokasi_pinjam' => 'required|string|max:255',
            'ruangan' => 'required|string|max:255',
        ]);

        // Ambil barang yang dipinjam
        $barang = DataBarang::find($request->id_databarang);

        if (!$barang) {
            Alert::error('Error', 'Barang tidak ditemukan!');
            return redirect()->back()->withInput();
        }

        // Cek apakah jumlah barang tersedia
        if ($request->jumlah > $barang->stok) {
            Alert::error('Error', 'Stok barang tidak mencukupi!');
            return redirect()->back()->withInput();
        }

        // Kurangi stok barang yang dipinjam
        $barang->stok -= $request->jumlah;
        $barang->save();

        // Buat peminjaman baru
        $peminjaman = new Peminjaman();
        $peminjaman->nama_peminjam = $request->nama_peminjam;
        $peminjaman->jenis_barang = $request->jenis_barang;
        $peminjaman->tanggal_pinjam = $request->tanggal_pinjam;
        $peminjaman->tanggal_kembali = $request->tanggal_kembali;
        $peminjaman->lokasi_awal = $request->lokasi_awal;
        $peminjaman->lokasi_pinjam = $request->lokasi_pinjam;
        $peminjaman->ruangan = $request->ruangan;
        $peminjaman->id_databarang = $request->id_databarang;
        $peminjaman->jumlah = $request->jumlah;
        $peminjaman->status = 'Dipinjam';
        $peminjaman->save();

        // Berikan notifikasi sukses
        Alert::success('Sukses', 'Peminjaman berhasil ditambahkan!');
        return redirect()->route('peminjaman.index');
    }

    public function edit($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        return view('peminjaman.edit', compact('peminjaman'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'jenis_barang' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'lokasi_awal' => 'required|string|max:255',
            'lokasi_pinjam' => 'required|string|max:255',
            'ruangan' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        // Cek apakah statusnya diubah menjadi 'dikembalikan'
        if ($request->status === 'dikembalikan' && $peminjaman->status !== 'dikembalikan') {
            $barang = DataBarang::find($peminjaman->id_databarang);
            if ($barang) {
                // Tambahkan kembali stok barang
                $barang->stok += $peminjaman->jumlah;
                $barang->save();
            }
        }

        // Update data peminjaman
        $peminjaman->update($request->only([
            'nama_peminjam', 'jenis_barang', 'tanggal_pinjam', 'tanggal_kembali',
            'lokasi_awal', 'lokasi_pinjam', 'ruangan', 'status'
        ]));

        // Berikan notifikasi sukses
        Alert::success('Sukses', 'Peminjaman berhasil diperbarui!');
        return redirect()->route('peminjaman.index');
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Tambahkan kembali stok barang jika peminjaman belum dikembalikan
        if ($peminjaman->status === 'dipinjam') {
            $barang = DataBarang::find($peminjaman->id_databarang);
            if ($barang) {
                $barang->stok += $peminjaman->jumlah;
                $barang->save();
            }
        }

        // Hapus peminjaman
        $peminjaman->delete();

        // Berikan notifikasi sukses
        Alert::success('Sukses', 'Peminjaman berhasil dihapus!');
        return redirect()->route('peminjaman.index');
    }
}
