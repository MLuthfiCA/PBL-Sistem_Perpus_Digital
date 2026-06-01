<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Buku;
use Illuminate\Database\Seeder;

class TestOverdueDataSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'mahasiswa')->first();

        if (!$user) {
            echo "No mahasiswa user found\n";
            return;
        }

        $books = Buku::limit(2)->get();

        if ($books->count() < 2) {
            echo "Not enough books in database\n";
            return;
        }

        // Create overdue loan 1 (5 days late, 25000 fine)
        $peminjaman1 = Peminjaman::create([
            'id_pengguna' => $user->id_pengguna,
            'tanggal_pinjam' => now()->subDays(20),
            'tanggal_kembali' => null,
            'batas_kembali' => now()->subDays(5),
            'status' => 'dipinjam',
            'denda' => 25000,
            'status_denda' => 'belum_lunas',
            'id_buku' => $books[0]->id_buku,
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman1->id_peminjaman,
            'id_buku' => $books[0]->id_buku,
            'jumlah' => 1,
            'batas_kembali_buku' => now()->subDays(5),
        ]);

        // Create overdue loan 2 (3 days late, 15000 fine)
        $peminjaman2 = Peminjaman::create([
            'id_pengguna' => $user->id_pengguna,
            'tanggal_pinjam' => now()->subDays(15),
            'tanggal_kembali' => null,
            'batas_kembali' => now()->subDays(3),
            'status' => 'terlambat',
            'denda' => 15000,
            'status_denda' => 'belum_lunas',
            'id_buku' => $books[1]->id_buku,
        ]);

        DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman2->id_peminjaman,
            'id_buku' => $books[1]->id_buku,
            'jumlah' => 1,
            'batas_kembali_buku' => now()->subDays(3),
        ]);

        echo "✓ Test overdue data created for user: " . $user->nama . "\n";
    }
}
