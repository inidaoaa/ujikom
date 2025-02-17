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
        $detailPeminjaman = DetailPeminjaman::with(['peminjaman', 'barang'])->get();
        return view('detailpeminjaman.index', compact('detailPeminjaman'));
    }
}
