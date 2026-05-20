<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('histories', function (Blueprint $table) {

            // Primary key internal database
            $table->id('history_id');

            // User terkait aktivitas
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');

            // Relasi transaksi peminjaman
            $table->foreignId('borrowing_id')
                  ->nullable()
                  ->constrained('borrowings', 'borrowing_id')
                  ->nullOnDelete();

            // Aktivitas
            $table->enum('activity', [
                'borrow',
                'return',
                'login',
                'logout',
                'register',
                'create_book',
                'update_book',
                'delete_book'
            ]);

            // Detail tambahan
            $table->text('description')->nullable();

            // Untuk keamanan / tracking (jika perlu)
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            // Admin/petugas yang melakukan aksi (jika perlu)
            $table->foreignId('performed_by')
                  ->nullable()
                  ->constrained('users', 'user_id')
                  ->nullOnDelete();

            $table->timestamps();

            // Soft delete
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};