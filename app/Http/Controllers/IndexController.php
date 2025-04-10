<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Databarang;
use App\Models\Pembelian;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\BarangMusnah;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $cards = [
            [
                'title' => 'Total Barang',
                'value' => $totalBarang,
                'subtitle' => 'Jumlah keseluruhan barang',
                'icon' => '📦'
            ],
            [
                'title' => 'Total Pembelian',
                'value' => $totalPembelian,
                'subtitle' => 'Transaksi pembelian',
                'icon' => '🛒'
            ],
            [
                'title' => 'Total Peminjaman',
                'value' => $totalPeminjaman,
                'subtitle' => 'Barang yang dipinjam',
                'icon' => '📤'
            ],
            [
                'title' => 'Total Pengembalian',
                'value' => $totalPengembalian,
                'subtitle' => 'Barang yang dikembalikan',
                'icon' => '📥'
            ],
            [
                'title' => 'Total Dimusnahkan',
                'value' => $totalMusnah,
                'subtitle' => 'Barang yang dimusnahkan',
                'icon' => '🗑️'
            ]
        ];
        

        // Grafik: Barang per jenis
        $jenisBarang = Databarang::select('jenis_barang', DB::raw('count(*) as total'))
            ->groupBy('jenis_barang')->get();
        $chartLabels = $jenisBarang->pluck('jenis_barang');
        $chartData = $jenisBarang->pluck('total');

        // Grafik: Pembelian per tahun
        $pembelianPerTahun = Pembelian::select('tahun_pembelian', DB::raw('SUM(jumlah) as total'))
            ->groupBy('tahun_pembelian')->orderBy('tahun_pembelian')->get();
        $pembelianLabels = $pembelianPerTahun->pluck('tahun_pembelian');
        $pembelianData = $pembelianPerTahun->pluck('total');

        // Grafik: Peminjaman per bulan tahun ini
        $peminjamanPerBulan = Peminjaman::select(
                DB::raw("MONTH(tanggal_pinjam) as bulan"),
                DB::raw("SUM(jumlah) as total")
            )
            ->whereYear('tanggal_pinjam', now()->year)
            ->groupBy(DB::raw("MONTH(tanggal_pinjam)"))
            ->orderBy(DB::raw("MONTH(tanggal_pinjam)"))
            ->get();

        $peminjamanLabels = $peminjamanPerBulan->pluck('bulan')->map(function ($bulan) {
            return date('F', mktime(0, 0, 0, $bulan, 10));
        });
        $peminjamanData = $peminjamanPerBulan->pluck('total');

        // Grafik: Barang musnah per tahun
        $musnahPerTahun = BarangMusnah::select(
                DB::raw("YEAR(tanggal_pemusnahan) as tahun"),
                DB::raw("SUM(jumlah) as total")
            )
            ->groupBy(DB::raw("YEAR(tanggal_pemusnahan)"))
            ->orderBy('tahun')
            ->get();

        $musnahLabels = $musnahPerTahun->pluck('tahun');
        $musnahData = $musnahPerTahun->pluck('total');

        return view('index', compact(
            'cards', 'chartLabels', 'chartData',
            'pembelianLabels', 'pembelianData',
            'peminjamanLabels', 'peminjamanData',
            'musnahLabels', 'musnahData'
        ));
    }
}
