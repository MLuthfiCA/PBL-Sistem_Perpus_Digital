<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {

            // Primary key internal database
            $table->id('borrowing_id');

            // Kode transaksi
            $table->string('borrowing_code')->unique();

            // Peminjam (mahasiswa)
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');

            // Tanggal pinjam
            $table->date('borrowing_date');

            // Batas pengembalian
            $table->date('return_limit');

            // Tanggal kembali
            $table->date('return_date')->nullable();

            // Status
            $table->enum('status', [
                'borrowed',
                'returned',
                'late'
            ])->default('borrowed');

            // Denda
            $table->decimal('fine', 10, 2)->default(0);

            // Status pembayaran denda
            $table->enum('fine_status', [
                'unpaid',
                'paid'
            ])->default('unpaid');

            // Catatan
            $table->text('notes')->nullable();

            $table->timestamps();

            // Soft delete
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};