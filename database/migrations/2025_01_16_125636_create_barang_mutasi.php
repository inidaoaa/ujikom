<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang_mutasi', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_barang');
            $table->string('tanggal_mutasi');
            $table->string('lokasi_awal');
            $table->string('lokasi_mutasi');
            $table->string('ruangan');
            $table->string('lantai');
            $table->string('keterangan');
            $table->unsignedBigInteger('id_databarang');
            $table->timestamps();


            $table->foreign('id_databarang')->references('id')->on('databarang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_mutasi');
    }
};
