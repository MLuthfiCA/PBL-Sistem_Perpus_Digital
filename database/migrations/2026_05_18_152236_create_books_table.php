<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {

            // Primary key internal database
            $table->id('buku_id');

            // Judul buku
            $table->string('judul');

            // Slug URL
            $table->string('slug')->unique();

            // Penulis
            $table->string('penulis');

            // Genre
            $table->string('genre')->nullable();

            // ISBN
            $table->string('isbn')->unique();

            // Penerbit
            $table->string('penerbit');

            // Tahun terbit
            $table->year('tahun_terbit');

            // Relasi kategori
            $table->foreignId('kategori_id')->constrained('kategori', 'kategori_id')->onDelete('cascade');

            // Bahasa
            $table->string('bahasa');

            // Cetakan (tambahan)
            $table->string('cetakan')->nullable();

            // Deskripsi
            $table->text('deskripsi')->nullable();

            // Cover buku
            $table->string('cover')->nullable();

            // Lokasi rak
            $table->string('lokasi_rak')->nullable();

            // Stok buku
            $table->integer('stok')->default(1);

            // Tampil Katalog (tambahan)
            $table->boolean('tampil_katalog')->default(true);

            // Status buku
            $table->enum('status', [
                'available',
                'borrowed',
                'lost',
                'maintenance'
            ])->default('available');

            $table->timestamps();

            // Soft delete
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};