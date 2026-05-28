<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Rename peminjaman table to peminjamans
        Schema::rename('peminjaman', 'peminjamans');

        // Update buku table - change status enum values to Indonesian
        Schema::table('buku', function (Blueprint $table) {
            $table->string('status')->change(); // Ubah dari enum ke string terlebih dahulu
        });

        // Update peminjamans table - change status enum values to Indonesian
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->string('status')->change(); // Ubah dari enum ke string terlebih dahulu
            $table->string('status_denda')->change(); // Ubah dari enum ke string terlebih dahulu
        });

        // Update detail_peminjaman table - change kondisi_kembali enum values to Indonesian
        Schema::table('detail_peminjaman', function (Blueprint $table) {
            $table->string('kondisi_kembali')->nullable()->change();
        });

        // Add lokasi_rak column to buku if it doesn't exist
        if (!Schema::hasColumn('buku', 'lokasi_rak')) {
            Schema::table('buku', function (Blueprint $table) {
                $table->string('lokasi_rak')->nullable()->after('cover');
            });
        }

        // Add bahasa column to buku if it doesn't exist
        if (!Schema::hasColumn('buku', 'bahasa')) {
            Schema::table('buku', function (Blueprint $table) {
                $table->string('bahasa')->default('Indonesia')->after('kategori_id');
            });
        }

        // Rename users table columns to match code expectations
        Schema::table('users', function (Blueprint $table) {
            // Add new columns with expected names
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->nullable()->after('full_name');
            }
            if (!Schema::hasColumn('users', 'id')) {
                $table->unsignedBigInteger('id')->nullable()->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert table renames
        Schema::rename('peminjamans', 'peminjaman');

        // Revert schema changes
        Schema::table('buku', function (Blueprint $table) {
            $table->enum('status', [
                'available',
                'borrowed',
                'lost',
                'maintenance'
            ])->default('available')->change();
        });

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->enum('status', [
                'borrowed',
                'returned',
                'late'
            ])->default('borrowed')->change();
            $table->enum('status_denda', [
                'unpaid',
                'paid'
            ])->default('unpaid')->change();
        });
    }
};
