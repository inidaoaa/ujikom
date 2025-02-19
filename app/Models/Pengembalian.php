<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';

    protected $fillable = [
        'nama_peminjam',
        'jenis_barang',
        'tanggal_pinjam',
        'tanggal_kembali',
        'lokasi_awal',
        'lokasi_pinjam',
        'ruangan',
        'jumlah',
        'status',
        'id_databarang',
        'id_peminjaman',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }

    public function databarang()
    {
        return $this->belongsTo(DataBarang::class, 'id_databarang');
    }
}
