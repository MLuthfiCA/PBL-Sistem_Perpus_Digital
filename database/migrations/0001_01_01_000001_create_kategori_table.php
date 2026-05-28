<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {

            // === Kolom ERD (KATEGORI) ===
            $table->id('id_kategori');
            $table->string('nama_kategori')->unique();
            $table->text('deskripsi')->nullable();

            // === Kolom tambahan (dipertahankan dari database lama) ===
            $table->string('slug')->unique()->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};
