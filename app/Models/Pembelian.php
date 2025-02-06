<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Pembelian extends Model
{

    use HasFactory;

    protected $table = 'pembelian';

    protected $fillable = [
        'id',
        'jenis_barang',
        'tahun_pembelian',
        'harga',
        'keterangan',
        'jumlah',
        'id_databarang',

    ];

    public function databarang() {
        return $this->belongsTo(Databarang::class, 'id_databarang');
    }
}
