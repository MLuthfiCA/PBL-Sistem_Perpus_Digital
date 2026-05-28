<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat', function (Blueprint $table) {

            // === Kolom ERD (RIWAYAT) ===
            $table->id('id_riwayat');

            // FK ke pengguna (ERD: RIWAYAT -- PENGGUNA)
            $table->foreignId('id_pengguna')->constrained('users', 'id_pengguna')->onDelete('cascade');

            // FK ke peminjaman (ERD: PEMINJAMAN 1 -- N RIWAYAT)
            $table->foreignId('id_peminjaman')
                  ->nullable()
                  ->constrained('peminjaman', 'id_peminjaman')
                  ->nullOnDelete();

            $table->date('tanggal');
            $table->string('aktivitas');

            // === Kolom tambahan (dipertahankan dari database lama) ===
            $table->text('deskripsi')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat');
    }
};
