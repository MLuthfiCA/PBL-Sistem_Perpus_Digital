<?php

namespace App\Http\Controllers;

// Import yang wajib ada agar tidak muncul error "Class not imported"
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'identity_number' => 'required',
            'password'        => 'required',
            'role'            => 'required'
        ]);

        // Map form role ke database role
        $roleMap = [
            'mahasiswa' => 'mahasiswa',
            'student'   => 'mahasiswa',
            'admin'     => 'admin'
        ];

        $databaseRole = $roleMap[$request->role] ?? $request->role;

        // Cari user berdasarkan identity_number (NIM/NIK)
        $user = User::where('identity_number', $request->identity_number)->first();

        if ($user && Auth::attempt(['identity_number' => $request->identity_number, 'password' => $request->password])) {
            $user = Auth::user();

            // Verify role matches
            if ($user->role !== $databaseRole) {
                Auth::logout();
                return back()->withErrors(['login_error' => 'ID Number and role do not match.']);
            }

            // Verify account status
            if ($user->status === 'inactive' || $user->status === 'suspended') {
                Auth::logout();
                return back()->withErrors(['login_error' => 'Your account status is ' . $user->status . '. Please contact the administrator.']);
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
                ->with('buku')
                ->get();

            // Hitung total denda
            $totalDenda = $booksOverdue->sum('denda');

            // Simpan data esensial ke session
            session(['user' => [
                'id'           => $user->id_pengguna,
                'name'         => $user->nama,
                'nim'          => $user->identity_number,
                'role'         => $user->role,
                'email'        => $user->email,
                'overdue_books' => $booksOverdue,
                'total_denda'  => $totalDenda,
            ]]);

            // Catat Riwayat Login
            \App\Models\Riwayat::create([
                'id_pengguna'  => $user->id_pengguna,
                'id_peminjaman' => null,
                'tanggal'      => now()->toDateString(),
                'aktivitas'    => 'Login',
                'deskripsi'    => 'User successfully logged into the system.',
                'ip_address'   => $request->ip(),
                'user_agent'   => $request->userAgent(),
            ]);

            if ($user->role == 'admin') {
                return redirect()->route('admin.katalog');
            } else {
                return redirect()->route('katalog');
            }
        }

        // Jika NIM/NIK atau password salah
        return back()->withErrors(['login_error' => 'Incorrect ID Number or password. Please try again.']);
    }
}
