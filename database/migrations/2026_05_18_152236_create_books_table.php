<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {

            // Primary key internal database
            $table->id('book_id');

            // Judul buku
            $table->string('title');

            // Slug URL
            $table->string('slug')->unique();

            // Penulis
            $table->string('author');

            // Genre
            $table->string('genre')->nullable();

            // ISBN
            $table->string('isbn')->unique();

            // Penerbit
            $table->string('publisher');

            // Tahun terbit
            $table->year('publication_year');

            // Relasi kategori
            $table->foreignId('category_id')->constrained('categories', 'category_id')->onDelete('cascade');

            // Bahasa
            $table->string('language');

            // Deskripsi
            $table->text('description')->nullable();

            // Cover buku
            $table->string('cover')->nullable();

            // Lokasi rak
            $table->string('rack_location')->nullable();

            // Stok buku
            $table->integer('stock')->default(1);

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
        Schema::dropIfExists('books');
    }
};