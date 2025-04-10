<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Databarang;
use App\Models\Pembelian;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\BarangMusnah;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Hitung data utama (stok total dan transaksi hari ini)
        $totalBarang = Databarang::sum('jumlah');
        $totalPembelian = Pembelian::whereDate('created_at', $today)->sum('jumlah');
        $totalPeminjaman = Peminjaman::whereDate('tanggal_pinjam', $today)->sum('jumlah');
        $totalPengembalian = Pengembalian::whereDate('tanggal_kembali', $today)->sum('jumlah');
        $totalMusnah = BarangMusnah::whereDate('tanggal_pemusnahan', $today)->sum('jumlah');

        $cards = [
            [
                'title' => 'Barang Hari Ini',
                'value' => $totalBarang,
                'subtitle' => 'Total stok tersedia',
                'icon' => '📦',
                'route' => route('databarang.index'),
            ],
            [
                'title' => 'Pembelian Hari Ini',
                'value' => $totalPembelian,
                'subtitle' => 'Pembelian masuk hari ini',
                'icon' => '🛒',
                'route' => route('pembelian.index'),
            ],
            [
                'title' => 'Peminjaman Hari Ini',
                'value' => $totalPeminjaman,
                'subtitle' => 'Barang dipinjam hari ini',
                'icon' => '📤',
                'route' => route('peminjaman.index'),
            ],
            [
                'title' => 'Pengembalian Hari Ini',
                'value' => $totalPengembalian,
                'subtitle' => 'Barang dikembalikan hari ini',
                'icon' => '📥',
                'route' => route('pengembalian.index'),
            ],
            [
                'title' => 'Dimusnahkan Hari Ini',
                'value' => $totalMusnah,
                'subtitle' => 'Barang dimusnahkan hari ini',
                'icon' => '🗑️',
                'route' => route('barangmusnah.index'),
            ],
        ];

        $recentActivities = collect([
            Pembelian::latest()->first()?->created_at ? [
                'icon' => '🛒',
                'text' => 'Pembelian barang dilakukan',
                'time' => Pembelian::latest()->first()->created_at->diffForHumans(),
            ] : null,
            Peminjaman::latest()->first()?->tanggal_pinjam ? [
                'icon' => '📤',
                'text' => 'Barang dipinjam',
                'time' => Carbon::parse(Peminjaman::latest()->first()->tanggal_pinjam)->diffForHumans(),
            ] : null,
            Pengembalian::latest()->first()?->tanggal_kembali ? [
                'icon' => '📥',
                'text' => 'Barang dikembalikan',
                'time' => Carbon::parse(Pengembalian::latest()->first()->tanggal_kembali)->diffForHumans(),
            ] : null,
            BarangMusnah::latest()->first()?->tanggal_pemusnahan ? [
                'icon' => '🗑️',
                'text' => 'Barang dimusnahkan',
                'time' => Carbon::parse(BarangMusnah::latest()->first()->tanggal_pemusnahan)->diffForHumans(),
            ] : null,
        ])->filter()->values();

        return view('index', compact('cards', 'recentActivities'));

    }
}
