<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {

            // Primary key internal database
            $table->id('kategori_id');

            // Nama kategori
            $table->string('nama_kategori')->unique();

            // Slug URL
            $table->string('slug')->unique();

            // Deskripsi kategori
            $table->text('deskripsi')->nullable();

            $table->timestamps();

            // Soft delete
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};