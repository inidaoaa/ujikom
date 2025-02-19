<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailPengembalian;
use App\Models\Pengembalian;
use App\Http\Controllers\Illuminate\Database\Eloquent\Collection;


class DetailPeminjamanController extends Controller
{
    /**
     * Menampilkan daftar riwayat detail peminjaman.
     */
    public function index()
    {
        $detailPengembalian = DetailPengembalian::with(['pengembalian', 'barang'])->paginate(10);
        $pengembalian = Pengembalian::all();
        return view('detailpengembalian.index', compact('detailPengembalian', 'pengembalian'));
    }
}
