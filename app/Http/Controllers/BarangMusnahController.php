<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMusnah;
use App\Models\DataBarang;
use Illuminate\Support\Facades\DB;
use Alert;

class BarangMusnahController extends Controller
{
    public function index(Request $request)
    {
        // alert
        $title = 'Hapus Data?';
        $text = "Isi Data tidak dapat kembali";
        confirmDelete($title, $text);
        //

        $databarang = DataBarang::all();
        $search = $request->input('search');
        $barangMusnah = BarangMusnah::with('dataBarang')
            ->when($search, function ($query, $search) {
                return $query->where('jenis_barang', 'like', "%{$search}%")
                    ->orWhere('lokasi_mutasi', 'like', "%{$search}%");
            })->paginate(10);

        return view('barangmusnah.index', compact('barangMusnah', 'databarang'));
    }

    public function create()
    {
        $databarang = DataBarang::all();
        return view('barangmusnah.create', compact('databarang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_databarang' => 'required|exists:databarang,id',
            'jenis_barang' => 'required|string|max:255',
            'tanggal_pemusnahan' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $databarang = DataBarang::findOrFail($request->id_databarang);

            if ($databarang->jumlah == 0) {
                return back()->with('error', 'Stok barang habis, tidak dapat dimusnahkan!')->withInput();
            }

            if ($databarang->jumlah < $request->jumlah) {
                return back()->with('error', 'Jumlah barang yang dimusnahkan melebihi stok yang tersedia!')->withInput();
            }

            // Kurangi stok di DataBarang
            $databarang->decrement('jumlah', $request->jumlah);

            // Insert ke barang_musnah dengan nama_barang
            BarangMusnah::create([
                'id_databarang' => $request->id_databarang,
                'nama_barang' => $databarang->nama_barang, 
                'jenis_barang' => $request->jenis_barang,
                'tanggal_pemusnahan' => $request->tanggal_pemusnahan,
                'keterangan' => $request->keterangan,
                'jumlah' => $request->jumlah,
            ]);

            DB::commit();

            Alert::success('Sukses!', 'Barang berhasil dimusnahkan dan jumlah barang diperbarui.');
            return redirect()->route('barangmusnah.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(string $id)
    {
        // Tambahkan fungsi edit jika diperlukan
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_databarang' => 'required|exists:databarang,id',
            'jenis_barang' => 'required|string|max:255',
            'tanggal_pemusnahan' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
        ]);

        $barangMusnah = BarangMusnah::findOrFail($id);
        $databarangLama = DataBarang::findOrFail($barangMusnah->id_databarang);
        $databarangBaru = DataBarang::findOrFail($request->id_databarang);

        $databarangLama->increment('jumlah', $barangMusnah->jumlah);

        if ($databarangBaru->jumlah < $request->jumlah) {
            return back()->with('error', 'Jumlah barang yang dimusnahkan melebihi stok yang tersedia!')->withInput();
        }

        $databarangBaru->decrement('jumlah', $request->jumlah);

        $barangMusnah->update([
            'id_databarang' => $request->id_databarang,
            'jenis_barang' => $request->jenis_barang,
            'tanggal_pemusnahan' => $request->tanggal_pemusnahan,
            'keterangan' => $request->keterangan,
            'jumlah' => $request->jumlah,
        ]);

        Alert::success('Sukses!', 'Data barang musnah berhasil diperbarui.');
        return redirect()->route('barangmusnah.index');
    }

    public function destroy(string $id)
    {
        $barangMusnah = BarangMusnah::findOrFail($id);
        $databarang = DataBarang::findOrFail($barangMusnah->id_databarang);

        $databarang->increment('jumlah', $barangMusnah->jumlah);
        $barangMusnah->delete();

        Alert::success('Sukses!', 'Data barang musnah berhasil dihapus dan jumlah barang dikembalikan.');
        return redirect()->route('barangmusnah.index');
    }
}
