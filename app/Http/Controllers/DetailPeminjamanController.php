<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailPeminjaman;

class DetailPeminjamanController extends Controller
{
    /**
     * Menampilkan daftar riwayat detail peminjaman.
     */
    public function index()
    {
        $detailPeminjaman = DetailPeminjaman::with(['peminjaman', 'barang'])->paginate(10);
        return view('detailpeminjaman.index', compact('detailPeminjaman'));
    }
}
