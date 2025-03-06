<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\DataBarang;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::all();
        $pengembalian = Pengembalian::paginate(10);
        return view('pengembalian.index', compact('pengembalian', 'peminjaman'));
    }

    public function create()
    {

    $peminjaman = Peminjaman::with('dataBarang')->where('status', 'Dipinjam')->get();
    return view('pengembalian.create', compact('peminjaman'));

    }


    public function store(Request $request)
    {
        $request->validate([
            'id_peminjaman' => 'required|exists:peminjaman,id',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($request->id_peminjaman);
            $databarang = DataBarang::findOrFail($peminjaman->id_databarang);

            // Kembalikan stok barang
            $databarang->increment('jumlah', $peminjaman->jumlah);

            // Simpan data pengembalian
            Pengembalian::create([
                'id_peminjaman' => $peminjaman->id,
                'id_databarang' => $peminjaman->id_databarang,
                'nama_peminjam' => $peminjaman->nama_peminjam,
                'jenis_barang' => $peminjaman->jenis_barang,
                'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'lokasi_awal' => $peminjaman->lokasi_awal,
                'lokasi_pinjam' => $peminjaman->lokasi_pinjam,
                'ruangan' => $peminjaman->ruangan,
                'jumlah' => $peminjaman->jumlah,
                'status' => $request->status,
            ]);

            // Ubah status peminjaman
            $peminjaman->update(['status' => 'Dikembalikan']);

            DB::commit();
            Alert::success('Sukses', 'Barang berhasil dikembalikan dan stok diperbarui.');
            return redirect()->route('pengembalian.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
