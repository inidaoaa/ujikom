<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Barangmutasi extends Model
{
    use Hasfactory;

    protected $table = 'barang_mutasi';

    protected $fillable = [
        'id',
        'jenis_barang',
        'tanggal_mutasi',
        'lokasi_awal',
        'lokasi_mutasi',
        'ruangan',
        'lantai',
        'keterangan',
        'id_databarang',
    ];

    public $timestamps = true;

    public function dataBarang() {
        return $this->belongsTo(DataBarang::class, 'id_databarang');
    }

}
