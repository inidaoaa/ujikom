<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $fillable = [
        'id',
        'nama_peminjam',
        'jenis_barang',
        'tanggal_pinjam',
        'tanggal_kembali',
        'lokasi_awal',
        'lokasi_pinjam',
        'ruangan',
        'jumlah',
        'status',
        'id_data_barang'
    ];

    public function databarang() {
        return this->belongsTo(Databarang::class);
    }
}
