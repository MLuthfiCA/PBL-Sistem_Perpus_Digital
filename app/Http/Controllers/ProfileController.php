<?php

namespace App\Http\Controllers;

class ProfileController extends Controller
{
    public function profile()
    {
        // This controller is deprecated. Use RiwayatController instead.
        // Redirecting to RiwayatController::tampilkanRiwayat()
        $user = session('user');
        if (!$user) return redirect('/login');

        $userId = $user['id'] ?? 1;

        try {
            // Fetch active loans (dipinjam)
            $peminjaman = \App\Models\Peminjaman::where('id_pengguna', $userId)
                ->where('status', 'dipinjam')
                ->with('buku')
                ->get();

            // Fetch return history (dikembalikan)
            $pengembalian = \App\Models\Peminjaman::where('id_pengguna', $userId)
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