<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $fillable = [
        'id',
        'nama_peminjam',
        'jenis_barang',
        'tanggal_pinjam',
        'lokasi_awal',
        'lokasi_pinjam',
        'ruangan',
        '',
    ];
}
