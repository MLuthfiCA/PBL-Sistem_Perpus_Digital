<?php

namespace App\Http\Controllers;

// Import yang wajib ada agar tidak muncul error "Class not imported"
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'role'     => 'required'
        ]);

        // Map form role ke database role
        $roleMap = [
            'mahasiswa' => 'mahasiswa',
            'student' => 'mahasiswa',
            'admin' => 'admin'
        ];

        $databaseRole = $roleMap[$request->role] ?? $request->role;

        // MELAKUKAN LOGIN ASLI KE DATABASE
        // Try dengan username terlebih dahulu
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();

            // Verify role matches
            if ($user->role !== $databaseRole) {
                Auth::logout();
                return back()->withErrors(['login_error' => 'Username and role do not match.']);
            }

            /** @var \App\Models\User $user */
$user = Auth::user();

            // Ambil data buku yang terlambat
            $booksOverdue = $user->peminjaman()
                ->where(function ($query) {
                    $query->where('status', 'terlambat')
                        ->orWhere(function ($q) {
                            $q->where('status', 'dipinjam')
                                ->whereNotNull('batas_kembali')
                                ->whereDate('batas_kembali', '<', now()->toDateString());
                        });
                })
                ->with('detailPeminjaman.buku')
                ->get();

            // Hitung total denda
            $totalDenda = $booksOverdue->sum('denda');

            // Simpan data esensial ke session agar sesuai dengan arsitektur saat ini
            session(['user' => [
                'id' => $user->id_pengguna,
                'name' => $user->nama,
                'role' => $user->role,
                'username' => $user->username,
                'email' => $user->email,
                'overdue_books' => $booksOverdue,
                'total_denda' => $totalDenda,
            ]]);

            if ($user->role == 'admin') {
                return redirect()->route('admin.katalog');
            } else {
                return redirect()->route('katalog');
            }
        }

        // Jika username/password salah
        return back()->withErrors(['login_error' => 'Incorrect username or password. Please try again.']);
    }
}
