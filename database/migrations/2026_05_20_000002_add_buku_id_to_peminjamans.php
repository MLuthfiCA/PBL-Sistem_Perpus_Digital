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
        // First, clean up orphaned records in peminjamans if buku_id is invalid
        try {
            DB::statement('DELETE FROM peminjamans WHERE buku_id NOT IN (SELECT buku_id FROM buku)');
        } catch (\Exception $e) {
            // buku_id column might not exist yet
        }

        // Add buku_id column to peminjamans table without constraint first
        Schema::table('peminjamans', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjamans', 'buku_id')) {
                $table->unsignedBigInteger('buku_id')->nullable()->after('user_id');
            }
        });

        // Add kode_peminjaman column if missing
        Schema::table('peminjamans', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjamans', 'kode_peminjaman')) {
                $table->string('kode_peminjaman')->unique()->after('peminjaman_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            if (Schema::hasColumn('peminjamans', 'buku_id')) {
                try {
                    $table->dropForeign(['buku_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist
                }
                $table->dropColumn('buku_id');
            }
        });
    }
};
