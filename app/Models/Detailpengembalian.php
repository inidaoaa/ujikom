<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengembalian extends Model
{
    use HasFactory;

    protected $table = 'detail_pengembalian'; // Nama tabel di database

    protected $fillable = [
        'id_pengembalian',
        'id_barang',
        'jumlah',
        'status_pengembalian'
    ];

    /**
     * Relasi ke tabel pengembalian (satu pengembalian memiliki banyak detail).
     */
    // public function pengembalian()
    // {
    //     return $this->belongsTo(Pengembalian::class);
    // }

    // public function barang()
    // {
    //     return $this->belongsTo(DataBarang::class);
    // }
}
