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
            $peminjaman = Peminjaman::where('id_pengguna', $userId)
                ->where('status', 'dipinjam')
                ->orWhere(function ($query) use ($userId) {
                    $query->where('id_pengguna', $userId)
                        ->where('status', 'terlambat');
                })
                ->with('buku')
                ->get();

            // Fetch return history (dikembalikan)
            $pengembalian = Peminjaman::where('id_pengguna', $userId)
                ->whereIn('status', ['dikembalikan', 'dibatalkan'])
                ->with('buku')
                ->orderBy('updated_at', 'desc')
                ->paginate(10);
        } catch (\Exception $e) {
            // Jika database error, gunakan empty collection
            $peminjaman = collect([]);
            $pengembalian = collect([]);
        }

        return view('user.pages.profile', compact('peminjaman', 'pengembalian'));
    }
}