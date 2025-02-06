<?php

namespace App\Http\Controllers;

use App\Models\Databarang;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua data dari tabel data_barang

        // Kirim data ke view
        return view('index');
    }
}
