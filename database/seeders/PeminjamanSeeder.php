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

        // 1. Pastikan ada User (mahasiswa)
        // Login menggunakan: identity_number (NIM) + password
        $user = User::first() ?? User::create([
            'nama'            => 'Rayyan',
            'email'           => 'rayyan@student.polibatam.ac.id',
            'password'        => Hash::make('password'),
            'role'            => 'mahasiswa',
            'identity_number' => '1234567890',   // NIM dipakai untuk login
        ]);

        // 2. Seed admin user
        // Login admin: identity_number = 00002 (NIK), password = admin123, role = admin
        $admin = User::where('role', 'admin')->first() ?? User::create([
            'nama'            => 'Admin Library',
            'email'           => 'admin@readspace.com',
            'password'        => Hash::make('admin123'),
            'role'            => 'admin',
            'identity_number' => '00002',        // NIK admin
        ]);

        // 3. Pastikan ada Penulis dan Penerbit
        $penulis1 = \App\Models\Penulis::firstOrCreate(['nama_penulis' => 'Andrea Hirata']);
        $penerbit1 = \App\Models\Penerbit::firstOrCreate(['nama_penerbit' => 'Bentang Pustaka']);

        $penulis2 = \App\Models\Penulis::firstOrCreate(['nama_penulis' => 'Tere Liye']);
        $penerbit2 = \App\Models\Penerbit::firstOrCreate(['nama_penerbit' => 'Gramedia Pustaka Utama']);

        $penulis3 = \App\Models\Penulis::firstOrCreate(['nama_penulis' => 'Henry Manampiring']);
        $penerbit3 = \App\Models\Penerbit::firstOrCreate(['nama_penerbit' => 'Kompas']);

        // 4. Pastikan ada Buku
        $buku1 = Buku::where('judul', 'Laskar Pelangi')->first() ?? Buku::create([
            'judul'        => 'Laskar Pelangi',
            'id_penulis'   => $penulis1->id_penulis,
            'isbn'         => '978-979-3062-79-1',
            'id_penerbit'  => $penerbit1->id_penerbit,
            'tahun_terbit' => 2005,
            'id_kategori'  => $kategoriNovel->id_kategori,
            'stok'         => 5,
            'status'       => 'Dipinjam',
        ]);

        $buku2 = Buku::where('judul', 'Bumi')->first() ?? Buku::create([
            'judul'        => 'Bumi',
            'id_penulis'   => $penulis2->id_penulis,
            'isbn'         => '978-602-03-3295-6',
            'id_penerbit'  => $penerbit2->id_penerbit,
            'tahun_terbit' => 2014,
            'id_kategori'  => $kategoriNovel->id_kategori,
            'stok'         => 3,
            'status'       => 'Dipinjam',
        ]);

        $buku3 = Buku::where('judul', 'Filosofi Teras')->first() ?? Buku::create([
            'judul'        => 'Filosofi Teras',
            'id_penulis'   => $penulis3->id_penulis,
            'isbn'         => '978-602-412-518-9',
            'id_penerbit'  => $penerbit3->id_penerbit,
            'tahun_terbit' => 2018,
            'id_kategori'  => $kategoriSelfHelp->id_kategori,
            'stok'         => 10,
            'status'       => 'Tersedia',
        ]);

        // 5. Tambahkan Data Peminjaman
        
        // Peminjaman yang sudah dikembalikan
        Peminjaman::create([
            'id_pengguna'    => $user->id_pengguna,
            'id_buku'        => $buku3->id_buku,
            'tanggal_pinjam' => date('Y-m-d', strtotime('-10 days')),
            'batas_kembali'  => date('Y-m-d', strtotime('-3 days')),
            'tanggal_kembali' => date('Y-m-d', strtotime('-3 days')),
            'status'         => 'dikembalikan',
            'denda'          => 0,
            'status_denda'   => 'lunas',
        ]);

        // Peminjaman yang hampir jatuh tempo (kembali dalam 2 hari)
        Peminjaman::create([
            'id_pengguna'    => $user->id_pengguna,
            'id_buku'        => $buku1->id_buku,
            'tanggal_pinjam' => date('Y-m-d', strtotime('-5 days')),
            'batas_kembali'  => date('Y-m-d', strtotime('+2 days')),
            'status'         => 'dipinjam',
            'denda'          => 0,
            'status_denda'   => 'lunas',
        ]);

        // Peminjaman baru hari ini
        Peminjaman::create([
            'id_pengguna'    => $user->id_pengguna,
            'id_buku'        => $buku2->id_buku,
            'tanggal_pinjam' => date('Y-m-d'),
            'batas_kembali'  => date('Y-m-d', strtotime('+7 days')),
            'status'         => 'dipinjam',
            'denda'          => 0,
            'status_denda'   => 'lunas',
        ]);
    }
}
