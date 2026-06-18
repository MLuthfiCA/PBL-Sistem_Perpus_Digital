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
        // 1. Create pivot table
        Schema::create('buku_penulis', function (Blueprint $table) {
            $table->unsignedBigInteger('id_buku');
            $table->unsignedBigInteger('id_penulis');
            
            // Set primary keys (composite)
            $table->primary(['id_buku', 'id_penulis']);
        });

        // 2. Migrate existing data
        if (Schema::hasColumn('buku', 'id_penulis')) {
            $books = DB::table('buku')->whereNotNull('id_penulis')->get();
            foreach ($books as $book) {
                DB::table('buku_penulis')->updateOrInsert(
                    ['id_buku' => $book->id_buku, 'id_penulis' => $book->id_penulis]
                );
            }

            // 3. Drop old column and foreign key
            Schema::table('buku', function (Blueprint $table) {
                // Drop foreign key if exists
                if (DB::getDriverName() !== 'sqlite') {
                    try {
                        $table->dropForeign(['id_penulis']);
                    } catch (\Exception $e) {}
                }
                $table->dropColumn('id_penulis');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Add back column
        Schema::table('buku', function (Blueprint $table) {
            $table->unsignedBigInteger('id_penulis')->nullable()->after('judul');
        });

        // 2. Migrate back (only one author can be saved back, we'll take the first one)
        $pivots = DB::table('buku_penulis')->get();
        foreach ($pivots as $pivot) {
            DB::table('buku')->where('id_buku', $pivot->id_buku)->update(['id_penulis' => $pivot->id_penulis]);
        }

        // 3. Drop pivot table
        Schema::dropIfExists('buku_penulis');
    }
};
