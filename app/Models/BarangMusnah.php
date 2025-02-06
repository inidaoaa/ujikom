<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMusnah extends Model
{
    protected $fillable = [
        'id',
        'nama_barang',
        'jenis_barang',
        'tanggal_pemusnahan',
        'keterangan',
        'jumlah',
        'id_data_barang',

    ];

    public function databarang() {
        return this->belongsTo(Databarang::class);
    }
}
