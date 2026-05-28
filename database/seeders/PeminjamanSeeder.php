<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Pastikan ada Kategori
        $kategoriNovel = \App\Models\Kategori::firstOrCreate([
            'nama_kategori' => 'Novel',
        ], [
            'deskripsi' => 'Kategori Buku Novel',
            'slug' => 'novel'
        ]);

        $kategoriSelfHelp = \App\Models\Kategori::firstOrCreate([
            'nama_kategori' => 'Self Improvement',
        ], [
            'deskripsi' => 'Kategori Pengembangan Diri',
            'slug' => 'self-improvement'
        ]);

        // 1. Pastikan ada User
        $user = User::first() ?? User::create([
            'nama' => 'Rayyan',
            'email' => 'rayyan@student.polibatam.ac.id',
            'username' => 'rayyan123',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'identity_number' => '1234567890',
        ]);

        // Seed an admin user for testing
        $admin = User::where('role', 'admin')->first() ?? User::create([
            'nama' => 'Admin Library',
            'email' => 'admin@readspace.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'identity_number' => '0000000001',
        ]);

        // 2. Pastikan ada Buku
        $buku1 = Buku::where('judul', 'Laskar Pelangi')->first() ?? Buku::create([
            'judul' => 'Laskar Pelangi',
            'penulis' => 'Andrea Hirata',
            'genre' => 'Drama',
            'isbn' => '978-979-3062-79-1',
            'penerbit' => 'Bentang Pustaka',
            'tahun_terbit' => 2005,
            'id_kategori' => $kategoriNovel->id_kategori,
            'stok' => 5,
            'status' => 'Dipinjam',
        ]);

        $buku2 = Buku::where('judul', 'Bumi')->first() ?? Buku::create([
            'judul' => 'Bumi',
            'penulis' => 'Tere Liye',
            'genre' => 'Fantasi',
            'isbn' => '978-602-03-3295-6',
            'penerbit' => 'Gramedia Pustaka Utama',
            'tahun_terbit' => 2014,
            'id_kategori' => $kategoriNovel->id_kategori,
            'stok' => 3,
            'status' => 'Dipinjam',
        ]);

        $buku3 = Buku::where('judul', 'Filosofi Teras')->first() ?? Buku::create([
            'judul' => 'Filosofi Teras',
            'penulis' => 'Henry Manampiring',
            'genre' => 'Self-Dev',
            'isbn' => '978-602-412-518-9',
            'penerbit' => 'Kompas',
            'tahun_terbit' => 2018,
            'id_kategori' => $kategoriSelfHelp->id_kategori,
            'stok' => 10,
            'status' => 'Tersedia',
        ]);

        // 3. Tambahkan Data Peminjaman
        
        // Peminjaman yang sudah dikembalikan
        Peminjaman::create([
            'id_pengguna' => $user->id_pengguna,
            'id_buku' => $buku3->id_buku,
            'tanggal_pinjam' => date('Y-m-d', strtotime('-10 days')),
            'batas_kembali' => date('Y-m-d', strtotime('-3 days')),
            'tanggal_kembali' => date('Y-m-d', strtotime('-3 days')),
            'status' => 'dikembalikan',
            'denda' => 0,
            'status_denda' => 'lunas',
        ]);

        // Peminjaman yang hampir jatuh tempo (kembali dalam 2 hari)
        Peminjaman::create([
            'id_pengguna' => $user->id_pengguna,
            'id_buku' => $buku1->id_buku,
            'tanggal_pinjam' => date('Y-m-d', strtotime('-5 days')),
            'batas_kembali' => date('Y-m-d', strtotime('+2 days')),
            'status' => 'dipinjam',
            'denda' => 0,
            'status_denda' => 'lunas',
        ]);

        // Peminjaman baru hari ini
        Peminjaman::create([
            'id_pengguna' => $user->id_pengguna,
            'id_buku' => $buku2->id_buku,
            'tanggal_pinjam' => date('Y-m-d'),
            'batas_kembali' => date('Y-m-d', strtotime('+7 days')),
            'status' => 'dipinjam',
            'denda' => 0,
            'status_denda' => 'lunas',
        ]);
    }
}
