<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Http\Controllers\Illuminate\Database\Eloquent\Collection;


class DetailPeminjamanController extends Controller
{
    /**
     * Menampilkan daftar riwayat detail peminjaman.
     */
    public function index()
    {
        $detailPeminjaman = DetailPeminjaman::with(['peminjaman', 'barang'])->paginate(10);
        $peminjaman = Peminjaman::all();
        return view('detailpeminjaman.index', compact('detailPeminjaman', 'peminjaman'));
    }
}
