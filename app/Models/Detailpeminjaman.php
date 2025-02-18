<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman';

    protected $fillable = [
        'id_peminjaman',
        'id_barang',
        'jumlah',
        'status_pengembalian'
    ];

    /**
     * Relasi ke tabel Peminjaman (satu peminjaman memiliki banyak detail).
     */
    // public function peminjaman()
    // {
    //     return $this->belongsTo(Peminjaman::class);
    // }

    // public function dataBarang()
    // {
    //     return $this->belongsTo(DataBarang::class);
    // }
}
