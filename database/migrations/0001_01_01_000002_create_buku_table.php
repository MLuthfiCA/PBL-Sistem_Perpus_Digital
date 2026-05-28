<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {

            // === Kolom ERD (BUKU) ===
            $table->id('id_buku');
            $table->string('judul');
            $table->string('penulis');
            $table->string('penerbit');
            $table->year('tahun_terbit');
            $table->string('isbn')->unique();
            $table->integer('stok')->default(1);
            $table->text('deskripsi')->nullable();

            // FK ke kategori (ERD: BUKU N -- 1 KATEGORI)
            $table->foreignId('id_kategori')->constrained('kategori', 'id_kategori')->onDelete('cascade');

            // === Kolom tambahan dari user (cetakan, genre, bahasa) ===
            $table->string('cetakan')->nullable();
            $table->string('genre')->nullable();
            $table->string('bahasa')->default('Indonesia');

            // === Kolom tambahan (dipertahankan dari database lama) ===
            $table->string('slug')->unique()->nullable();
            $table->string('cover')->nullable();
            $table->string('lokasi_rak')->nullable();
            $table->boolean('tampil_katalog')->default(true);
            $table->enum('status', ['Tersedia', 'Dipinjam', 'Hilang', 'Perawatan'])->default('Tersedia');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
