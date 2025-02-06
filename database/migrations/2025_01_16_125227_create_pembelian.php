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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_barang');
            $table->string('tahun_pembelian');
            $table->string('harga');
            $table->string('keterangan');
            $table->unsignedBigInteger('jumlah');
            $table->unsignedBigInteger('id_databarang')->nullable(); // Menambahkan nullable
            $table->timestamps();

            // Menambahkan foreign key
            $table->foreign('id_databarang')->references('id')->on('databarang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
