<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_peminjaman', function (Blueprint $table) {

            // Primary key internal database
            $table->id('detail_peminjaman_id');

            // Relasi ke transaksi peminjaman
            $table->foreignId('peminjaman_id')
                  ->constrained('peminjaman', 'peminjaman_id')
                  ->onDelete('cascade');

            // Relasi ke buku
            $table->foreignId('buku_id')->constrained('buku', 'buku_id')->onDelete('cascade');

            // Jumlah buku
            $table->integer('jumlah')->default(1);

            // Deadline khusus buku
            $table->date('batas_kembali_buku')->nullable();

            // Kondisi saat dikembalikan (rusak, dll)
            $table->enum('kondisi_kembali', [
                'good',
                'damaged',
                'lost'
            ])->nullable();

            // Denda per item
            $table->decimal('denda_per_item', 10, 2)->default(0);

            // Waktu dikembalikan
            $table->timestamp('dikembalikan_pada')->nullable();

            // Catatan tambahan
            $table->text('catatan')->nullable();

            $table->timestamps();

            // Soft delete
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};