<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowing_details', function (Blueprint $table) {

            // Primary key internal database
            $table->id('borrowing_detail_id');

            // Relasi ke transaksi peminjaman
            $table->foreignId('borrowing_id')
                  ->constrained('borrowings', 'borrowing_id')
                  ->onDelete('cascade');

            // Relasi ke buku
            $table->foreignId('book_id')->constrained('books', 'book_id')->onDelete('cascade');

            // Jumlah buku
            $table->integer('quantity')->default(1);

            // Deadline khusus buku
            $table->date('due_date')->nullable();

            // Kondisi saat dikembalikan (rusak, dll)
            $table->enum('return_condition', [
                'good',
                'damaged',
                'lost'
            ])->nullable();

            // Denda per item
            $table->decimal('fine_per_item', 10, 2)->default(0);

            // Waktu dikembalikan
            $table->timestamp('returned_at')->nullable();

            // Catatan tambahan
            $table->text('notes')->nullable();

            $table->timestamps();

            // Soft delete
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowing_details');
    }
};