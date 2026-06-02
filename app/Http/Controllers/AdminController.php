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

    public function profile(\Illuminate\Http\Request $request)
    {
        $query = Peminjaman::with(['user', 'buku']);

        // Search by borrower name or book title
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('nama', 'like', "%{$search}%")
                       ->orWhere('username', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('buku', function ($bq) use ($search) {
                    $bq->where('judul', 'like', "%{$search}%");
                });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        $books = $query->orderBy('created_at', 'desc')
            ->paginate(5)
            ->withQueryString();

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
