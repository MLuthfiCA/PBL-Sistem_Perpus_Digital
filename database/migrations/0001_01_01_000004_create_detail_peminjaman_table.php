<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_peminjaman', function (Blueprint $table) {

            // === Kolom ERD (DETAIL_PEMINJAMAN) ===
            $table->id('id_detail');

            // FK ke peminjaman (ERD: PEMINJAMAN 1 -- N DETAIL_PEMINJAMAN)
            $table->foreignId('id_peminjaman')->constrained('peminjaman', 'id_peminjaman')->onDelete('cascade');

            // FK ke buku (ERD: BUKU referenced by DETAIL_PEMINJAMAN)
            $table->foreignId('id_buku')->constrained('buku', 'id_buku')->onDelete('cascade');

            $table->integer('jumlah')->default(1);

            // === Kolom tambahan (dipertahankan dari database lama) ===
            $table->date('batas_kembali_buku')->nullable();
            $table->string('kondisi_kembali')->nullable();
            $table->decimal('denda_per_item', 10, 2)->default(0);
            $table->timestamp('dikembalikan_pada')->nullable();
            $table->text('catatan')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};
