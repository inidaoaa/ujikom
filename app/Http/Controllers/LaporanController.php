<?php


namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\BarangMusnah;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    // Menampilkan halaman laporan ringkasan per bulan dengan filter
    public function showLaporan(Request $request)
    {
        // Ambil data filter dari request
        $tahun   = $request->input('tahun');
        $bulan   = $request->input('bulan');
        $tanggal = $request->input('tanggal');

        // Helper untuk menerapkan filter ke query builder
        $applyFilter = function ($query, $column) use ($tahun, $bulan, $tanggal) {
            if ($tahun) {
                $query->whereYear($column, $tahun);
            }
            if ($bulan) {
                $query->whereMonth($column, $bulan);
            }
            if ($tanggal) {
                $query->whereDay($column, $tanggal);
            }
            return $query;
        };

        // Ambil data terfilter dari masing-masing model dan pluck total per bulan
        $pembelian = $applyFilter(Pembelian::query(), 'tahun_pembelian')
            ->selectRaw("MONTH(tahun_pembelian) as bulan, SUM(jumlah) as total")
            ->groupByRaw("MONTH(tahun_pembelian)")
            ->pluck('total', 'bulan');

        $peminjaman = $applyFilter(Peminjaman::query(), 'tanggal_pinjam')
            ->selectRaw("MONTH(tanggal_pinjam) as bulan, SUM(jumlah) as total")
            ->groupByRaw("MONTH(tanggal_pinjam)")
            ->pluck('total', 'bulan');

        $pengembalian = $applyFilter(Pengembalian::query(), 'tanggal_kembali')
            ->selectRaw("MONTH(tanggal_kembali) as bulan, SUM(jumlah) as total")
            ->groupByRaw("MONTH(tanggal_kembali)")
            ->pluck('total', 'bulan');

        $barangMusnah = $applyFilter(BarangMusnah::query(), 'tanggal_pemusnahan')
            ->selectRaw("MONTH(tanggal_pemusnahan) as bulan, SUM(jumlah) as total")
            ->groupByRaw("MONTH(tanggal_pemusnahan)")
            ->pluck('total', 'bulan');

        // Tentukan bulan-bulan yang punya data
        $months = collect(array_merge(
            $pembelian->keys()->toArray(),
            $peminjaman->keys()->toArray(),
            $pengembalian->keys()->toArray(),
            $barangMusnah->keys()->toArray()
        ))->unique()->sort()->values();

        // Tambahkan semua bulan jika tidak ada filter
        if (!$tahun && !$bulan && !$tanggal) {
            $months = collect(range(1, 12)); // Semua bulan 1-12
        }

        // Bangun laporan hanya untuk bulan-bulan tersebut
        $laporan = $months->map(function ($m) use ($pembelian, $peminjaman, $pengembalian, $barangMusnah, $tahun) {
            return [
                'bulan'         => $m,
                'tahun'         => $tahun,
                'pembelian'     => $pembelian[$m] ?? 0,
                'peminjaman'    => $peminjaman[$m] ?? 0,
                'pengembalian'  => $pengembalian[$m] ?? 0,
                'barang_musnah' => $barangMusnah[$m] ?? 0,
            ];
        })->toArray();

        // Kirim data ke view
        return view('laporan', compact('laporan', 'tahun', 'bulan', 'tanggal'));
    }
}
