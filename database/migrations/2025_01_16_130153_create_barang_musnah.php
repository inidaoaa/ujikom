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
        Schema::create('barang_musnah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->string('jenis_barang');
            $table->string('tanggal_pemusnahan');
            $table->string('keterangan');
            $table->unsignedBigInteger('jumlah');
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
        Schema::dropIfExists('barang_musnah');
    }
};
