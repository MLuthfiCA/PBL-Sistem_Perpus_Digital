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
        // Update existing status values to match enum
        DB::table('buku')->where('status', 'Tersedia')->update(['status' => 'available']);
        DB::table('buku')->where('status', 'Dipinjam')->update(['status' => 'borrowed']);
        DB::table('buku')->where('status', 'Hilang')->update(['status' => 'lost']);
        DB::table('buku')->where('status', 'Perawatan')->update(['status' => 'maintenance']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
