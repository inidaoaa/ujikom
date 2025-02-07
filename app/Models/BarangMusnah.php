<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangMusnah extends Model
{

    use Hasfactory;

    protected $table = 'barang_musnah';

    protected $fillable = [
        'id',
        'nama_barang',
        'jenis_barang',
        'tanggal_pemusnahan',
        'keterangan',
        'jumlah',
        'id_databarang',

    ];

    public function dataBarang() {
        return $this->belongsTo(DataBarang::class, 'id_databarang');
    }
}
