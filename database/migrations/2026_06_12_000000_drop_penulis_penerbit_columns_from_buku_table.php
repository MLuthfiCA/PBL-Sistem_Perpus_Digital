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
        // 1. Create penulis table if not exists
        if (!Schema::hasTable('penulis')) {
            Schema::create('penulis', function (Blueprint $table) {
                $table->id('id_penulis');
                $table->string('nama_penulis')->unique();
                $table->timestamps();
            });
        }

        // 2. Create penerbit table if not exists
        if (!Schema::hasTable('penerbit')) {
            Schema::create('penerbit', function (Blueprint $table) {
                $table->id('id_penerbit');
                $table->string('nama_penerbit')->unique();
                $table->timestamps();
            });
        }

        // 3. Add id_penulis and id_penerbit to buku table if they do not exist
        Schema::table('buku', function (Blueprint $table) {
            if (!Schema::hasColumn('buku', 'id_penulis')) {
                $table->unsignedBigInteger('id_penulis')->nullable()->after('judul');
            }
            if (!Schema::hasColumn('buku', 'id_penerbit')) {
                $table->unsignedBigInteger('id_penerbit')->nullable()->after('id_penulis');
            }
        });

        // 4. Migrate string data to normalized tables and set foreign keys
        if (Schema::hasColumn('buku', 'penulis')) {
            $books = DB::table('buku')->whereNotNull('penulis')->where('penulis', '!=', '')->get();
            foreach ($books as $book) {
                if (empty($book->id_penulis)) {
                    $penulisId = DB::table('penulis')->where('nama_penulis', $book->penulis)->value('id_penulis');
                    if (!$penulisId) {
                        $penulisId = DB::table('penulis')->insertGetId([
                            'nama_penulis' => $book->penulis,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    DB::table('buku')->where('id_buku', $book->id_buku)->update(['id_penulis' => $penulisId]);
                }
            }
        }

        if (Schema::hasColumn('buku', 'penerbit')) {
            $books = DB::table('buku')->whereNotNull('penerbit')->where('penerbit', '!=', '')->get();
            foreach ($books as $book) {
                if (empty($book->id_penerbit)) {
                    $penerbitId = DB::table('penerbit')->where('nama_penerbit', $book->penerbit)->value('id_penerbit');
                    if (!$penerbitId) {
                        $penerbitId = DB::table('penerbit')->insertGetId([
                            'nama_penerbit' => $book->penerbit,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    DB::table('buku')->where('id_buku', $book->id_buku)->update(['id_penerbit' => $penerbitId]);
                }
            }
        }

        // 5. Drop redundant columns and add foreign key constraints
        Schema::table('buku', function (Blueprint $table) {
            if (Schema::hasColumn('buku', 'penulis')) {
                $table->dropColumn('penulis');
            }
            if (Schema::hasColumn('buku', 'penerbit')) {
                $table->dropColumn('penerbit');
            }
            
            // Add constraints (only if supported)
            try {
                $table->foreign('id_penulis')->references('id_penulis')->on('penulis')->onDelete('set null');
                $table->foreign('id_penerbit')->references('id_penerbit')->on('penerbit')->onDelete('set null');
            } catch (\Exception $e) {}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            try {
                $table->dropForeign(['id_penulis']);
                $table->dropForeign(['id_penerbit']);
            } catch (\Exception $e) {}
            
            $table->string('penulis')->nullable();
            $table->string('penerbit')->nullable();
        });

        // Restore string data
        $books = DB::table('buku')
            ->leftJoin('penulis', 'buku.id_penulis', '=', 'penulis.id_penulis')
            ->leftJoin('penerbit', 'buku.id_penerbit', '=', 'penerbit.id_penerbit')
            ->select('buku.id_buku', 'penulis.nama_penulis', 'penerbit.nama_penerbit')
            ->get();

        foreach ($books as $book) {
            DB::table('buku')->where('id_buku', $book->id_buku)->update([
                'penulis' => $book->nama_penulis,
                'penerbit' => $book->nama_penerbit,
            ]);
        }

        Schema::table('buku', function (Blueprint $table) {
            $table->dropColumn('id_penulis');
            $table->dropColumn('id_penerbit');
        });

        Schema::dropIfExists('penulis');
        Schema::dropIfExists('penerbit');
    }
};
