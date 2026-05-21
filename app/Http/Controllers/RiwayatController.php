<?php

namespace App\Http\Controllers;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function tampilkanRiwayat()
    {
        $user = session('user');
        if (!$user) return redirect('/login');

        $userId = $user['id'] ?? 1;

        try {
            // Fetch active loans (dipinjam)
            $peminjaman = Peminjaman::where('user_id', $userId)
                ->where('status', 'dipinjam')
                ->with('buku')
                ->get();

            // Fetch return history (dikembalikan)
            $pengembalian = Peminjaman::where('user_id', $userId)
                ->where('status', 'dikembalikan')
                ->with('buku')
                ->orderBy('updated_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            // Jika database error, gunakan empty collection
            $peminjaman = collect([]);
            $pengembalian = collect([]);
        }

        return view('user.pages.profile', compact('peminjaman', 'pengembalian'));
    }
}