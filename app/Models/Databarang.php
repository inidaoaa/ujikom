<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Databarang extends Model
{
    use HasFactory;

    protected $table = 'databarang';

    protected $fillable = [
    'id',
    'nama_barang',
    'jenis_barang',
    'merek',

    ];

    public function barangmutasi() {
        return $this->hasMany(BarangMutasi::class);
    }

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class, 'id_databarang');
    }

    public function barangMusnah()
    {
    return $this->hasMany(BarangMusnah::class, 'id_databarang');
    }

    public function peminjaman()
    {
    return $this->hasMany(Peminjaman::class, 'id_databarang');
    }



}
