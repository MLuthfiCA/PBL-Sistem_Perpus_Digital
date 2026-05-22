<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Peminjaman;


class AdminController extends Controller
{
    public function tampilkanDataUser()
    {
        // Mengambil data dari database (tabel users)
        $users = User::all();
        return view('admin.pages.datauser', compact('users'));
    }
    
    public function destroy($id)
    {
        // Find and delete user by id
        User::findOrFail($id)->delete();
        return redirect('/admin/data_user')->with('success', 'User deleted successfully');
    }

    public function profile()
    {
        // Mengambil semua data peminjaman agar tabel tidak kosong
        $books = Peminjaman::with(['user', 'buku'])
            ->orderBy('status', 'desc') // 'dipinjam' akan muncul di atas 'dikembalikan'
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.pages.profile', compact('books'));
    }

    public function accPengembalian($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        // Hitung denda jika terlambat
        $denda = 0;
        if (strtotime($peminjaman->batas_kembali) < time()) {
            $hari_terlambat = ceil((time() - strtotime($peminjaman->batas_kembali)) / (60 * 60 * 24));
            $denda = max(0, $hari_terlambat * 5000);
        }

        // Update status peminjaman
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
            'denda' => $denda,
            'status_denda' => $denda > 0 ? 'belum_lunas' : 'lunas',
        ]);

        // Update status buku (increase stock)
        $buku = $peminjaman->buku;
        if ($buku) {
            $buku->update([
                'stok' => $buku->stok + 1,
            ]);
        }

        return redirect()->back()->with('success', 'Book return confirmed successfully!');
    }

    public function bayarDenda($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status_denda' => 'lunas']);

        return redirect()->back()->with('success', 'Fine marked as paid!');
    }
}
