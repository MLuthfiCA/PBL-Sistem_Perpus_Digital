<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update buku status to use Indonesian values
        Schema::table('buku', function (Blueprint $table) {
            // First, convert existing English values to Indonesian
            DB::statement("UPDATE buku SET status = 'tersedia' WHERE status = 'available'");
            DB::statement("UPDATE buku SET status = 'dipinjam' WHERE status = 'borrowed'");
            DB::statement("UPDATE buku SET status = 'hilang' WHERE status = 'lost'");
            DB::statement("UPDATE buku SET status = 'pemeliharaan' WHERE status = 'maintenance'");
        });

        // Update peminjamans status to use Indonesian values
        Schema::table('peminjamans', function (Blueprint $table) {
            DB::statement("UPDATE peminjamans SET status = 'dikembalikan' WHERE status = 'returned'");
            DB::statement("UPDATE peminjamans SET status = 'dipinjam' WHERE status = 'borrowed'");
            DB::statement("UPDATE peminjamans SET status = 'telat' WHERE status = 'late'");
        });

        // Update peminjamans status_denda to use Indonesian values
        Schema::table('peminjamans', function (Blueprint $table) {
            DB::statement("UPDATE peminjamans SET status_denda = 'lunas' WHERE status_denda = 'paid'");
            DB::statement("UPDATE peminjamans SET status_denda = 'belum lunas' WHERE status_denda = 'unpaid'");
        });

        // Update detail_peminjaman kondisi_kembali to use Indonesian values
        Schema::table('detail_peminjaman', function (Blueprint $table) {
            DB::statement("UPDATE detail_peminjaman SET kondisi_kembali = 'baik' WHERE kondisi_kembali = 'good'");
            DB::statement("UPDATE detail_peminjaman SET kondisi_kembali = 'rusak' WHERE kondisi_kembali = 'damaged'");
            DB::statement("UPDATE detail_peminjaman SET kondisi_kembali = 'hilang' WHERE kondisi_kembali = 'lost'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to English
        Schema::table('buku', function (Blueprint $table) {
            DB::statement("UPDATE buku SET status = 'available' WHERE status = 'tersedia'");
            DB::statement("UPDATE buku SET status = 'borrowed' WHERE status = 'dipinjam'");
            DB::statement("UPDATE buku SET status = 'lost' WHERE status = 'hilang'");
            DB::statement("UPDATE buku SET status = 'maintenance' WHERE status = 'pemeliharaan'");
        });

        Schema::table('peminjamans', function (Blueprint $table) {
            DB::statement("UPDATE peminjamans SET status = 'returned' WHERE status = 'dikembalikan'");
            DB::statement("UPDATE peminjamans SET status = 'borrowed' WHERE status = 'dipinjam'");
            DB::statement("UPDATE peminjamans SET status = 'late' WHERE status = 'telat'");
        });

        Schema::table('peminjamans', function (Blueprint $table) {
            DB::statement("UPDATE peminjamans SET status_denda = 'paid' WHERE status_denda = 'lunas'");
            DB::statement("UPDATE peminjamans SET status_denda = 'unpaid' WHERE status_denda = 'belum lunas'");
        });

        Schema::table('detail_peminjaman', function (Blueprint $table) {
            DB::statement("UPDATE detail_peminjaman SET kondisi_kembali = 'good' WHERE kondisi_kembali = 'baik'");
            DB::statement("UPDATE detail_peminjaman SET kondisi_kembali = 'damaged' WHERE kondisi_kembali = 'rusak'");
            DB::statement("UPDATE detail_peminjaman SET kondisi_kembali = 'lost' WHERE kondisi_kembali = 'hilang'");
        });
    }
};
