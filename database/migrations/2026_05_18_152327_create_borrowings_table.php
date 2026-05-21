<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {

            // Primary key internal database
            $table->id('peminjaman_id');

            // Kode transaksi
            $table->string('kode_peminjaman')->unique();

            // Peminjam (mahasiswa)
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');

            // Tanggal pinjam
            $table->date('tanggal_pinjam');

            // Batas pengembalian
            $table->date('batas_kembali');

            // Tanggal kembali
            $table->date('tanggal_kembali')->nullable();

            // Status
            $table->enum('status', [
                'borrowed',
                'returned',
                'late'
            ])->default('borrowed');

            // Denda
            $table->decimal('denda', 10, 2)->default(0);

            // Status pembayaran denda
            $table->enum('status_denda', [
                'unpaid',
                'paid'
            ])->default('unpaid');

            // Catatan
            $table->text('catatan')->nullable();

            $table->timestamps();

            // Soft delete
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};