<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {

            // === Kolom ERD (PEMINJAMAN) ===
            $table->id('id_peminjaman');

            // FK ke pengguna (ERD: PENGGUNA 1 -- N PEMINJAMAN)
            $table->foreignId('id_pengguna')->constrained('users', 'id_pengguna')->onDelete('cascade');

            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam');

            // === Kolom tambahan dari user (denda) ===
            $table->decimal('denda', 10, 2)->default(0);

            // === Kolom tambahan (dipertahankan dari database lama) ===
            $table->string('kode_peminjaman')->unique()->nullable();
            $table->date('batas_kembali')->nullable();
            $table->enum('status_denda', ['lunas', 'belum_lunas'])->default('lunas');
            $table->text('catatan')->nullable();

            // FK ke buku (dipertahankan, relasi langsung)
            $table->unsignedBigInteger('id_buku')->nullable();
            $table->foreign('id_buku')->references('id_buku')->on('buku')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
