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
        // 1. Insert existing unique genres into kategori table
        $genres = DB::table('buku')
                    ->whereNotNull('genre')
                    ->where('genre', '!=', '')
                    ->select('genre')
                    ->distinct()
                    ->pluck('genre');

        foreach ($genres as $genreName) {
            // Only insert if it doesn't already exist
            $exists = DB::table('kategori')->where('nama_kategori', $genreName)->exists();
            if (!$exists) {
                DB::table('kategori')->insert([
                    'nama_kategori' => $genreName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 2. Add id_kategori column
        Schema::table('buku', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kategori')->nullable()->after('deskripsi');
        });

        // 3. Update id_kategori based on genre string
        $kategoris = DB::table('kategori')->get();
        foreach ($kategoris as $kategori) {
            DB::table('buku')
                ->where('genre', $kategori->nama_kategori)
                ->update(['id_kategori' => $kategori->id_kategori]);
        }

        // 4. Drop genre column
        Schema::table('buku', function (Blueprint $table) {
            $table->dropColumn('genre');
            
            // Add foreign key constraint
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->dropForeign(['id_kategori']);
            $table->string('genre')->nullable()->after('cetakan');
        });

        // Restore genre string from kategori table
        $kategoris = DB::table('kategori')->get();
        foreach ($kategoris as $kategori) {
            DB::table('buku')
                ->where('id_kategori', $kategori->id_kategori)
                ->update(['genre' => $kategori->nama_kategori]);
        }

        Schema::table('buku', function (Blueprint $table) {
            $table->dropColumn('id_kategori');
        });
    }
};
