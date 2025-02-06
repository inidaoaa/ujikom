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
            Schema::create('peminjaman', function (Blueprint $table) {
                $table->id();
                $table->string('nama_peminjam');
                $table->string('jenis_barang');
                $table->string('tanggal_pinjam');
                $table->string('tanggal_kembali');
                $table->string('lokasi_awal');
                $table->string('lokasi_pinjam');
                $table->string('ruangan');
                $table->unsignedBigInteger('jumlah');
                $table->string('status');
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
            Schema::dropIfExists('peminjaman');
        }
    };
