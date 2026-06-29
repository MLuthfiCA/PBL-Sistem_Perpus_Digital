<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;


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
        $admin = session('user');
        
        $totalBuku = \App\Models\Buku::count();
        $totalUser = User::where('role', 'mahasiswa')->count();
        $totalPeminjaman = Peminjaman::count();
        
        $recentActivities = \App\Models\Riwayat::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.pages.profile', compact('admin', 'totalBuku', 'totalUser', 'totalPeminjaman', 'recentActivities'));
    }

    public function manageData(\Illuminate\Http\Request $request)
    {
        $query = Peminjaman::with(['user', 'buku']);

        // Search by borrower name or book title
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('nama', 'like', "%{$search}%")
                       ->orWhere('identity_number', 'like', "%{$search}%")
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

        // ===== LAPORAN DATA =====
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        $laporanQuery = Peminjaman::whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun);

        $totalDipinjam   = (clone $laporanQuery)->count();
        $sudahKembali    = (clone $laporanQuery)->where('status', 'dikembalikan')->count();
        $sedangDipinjam  = (clone $laporanQuery)->where('status', 'dipinjam')->count();
        $totalDenda      = (clone $laporanQuery)->sum('denda');

        // Buku paling sering dipinjam bulan ini
        $bukuTerpopuler = Peminjaman::with('buku')
            ->whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun)
            ->whereNotNull('id_buku')
            ->select('id_buku', DB::raw('count(*) as total'))
            ->groupBy('id_buku')
            ->orderByDesc('total')
            ->limit(3)
            ->get();

        // Anggota paling aktif bulan ini
        $anggotaAktif = Peminjaman::with('user')
            ->whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun)
            ->whereNotNull('id_pengguna')
            ->select('id_pengguna', DB::raw('count(*) as total'))
            ->groupBy('id_pengguna')
            ->orderByDesc('total')
            ->limit(3)
            ->get();

        // Buku yang sedang dipinjam
        $bukuSedangDipinjam = Peminjaman::with('buku')
            ->whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun)
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_pinjam', 'desc')
            ->limit(10)
            ->get();

        // Daftar bulan-tahun yang tersedia (untuk dropdown)
        $availableMonths = Peminjaman::selectRaw('YEAR(tanggal_pinjam) as tahun, MONTH(tanggal_pinjam) as bulan')
            ->groupByRaw('YEAR(tanggal_pinjam), MONTH(tanggal_pinjam)')
            ->orderByRaw('YEAR(tanggal_pinjam) DESC, MONTH(tanggal_pinjam) DESC')
            ->get();

        return view('admin.pages.manage-data', compact(
            'books',
            'totalDipinjam', 'sudahKembali', 'sedangDipinjam', 'totalDenda',
            'bukuTerpopuler', 'anggotaAktif', 'bukuSedangDipinjam', 'availableMonths',
            'bulan', 'tahun'
        ));
    }

    public function accPengembalian($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        // Hitung denda jika terlambat
        $denda = $peminjaman->calculateDenda();

        // Update status peminjaman
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
            'denda' => $denda,
            'status_denda' => $denda > 0 ? 'belum_lunas' : 'lunas',
        ]);

        // Update status buku — pakai DB langsung (lebih robust, tanpa Eloquent relationship)
        if ($peminjaman->id_buku) {
            DB::table('buku')
                ->where('id_buku', $peminjaman->id_buku)
                ->update([
                    'stok'       => DB::raw('stok + 1'),
                    'status'     => 'Tersedia',
                    'updated_at' => now(),
                ]);
        }

        // Catat Riwayat Pengembalian
        \App\Models\Riwayat::create([
            'id_pengguna' => $peminjaman->id_pengguna,
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'tanggal' => now()->toDateString(),
            'aktivitas' => 'Pengembalian Buku',
            'deskripsi' => 'Buku telah dikembalikan ke perpustakaan. Denda: Rp ' . number_format($denda, 0, ',', '.'),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Book return confirmed successfully!');
    }

    public function bayarDenda($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status_denda' => 'lunas']);

        return redirect()->back()->with('success', 'Fine marked as paid!');
    }

    public function accPengambilan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['is_diambil' => true]);

        // Catat Riwayat Pengambilan
        \App\Models\Riwayat::create([
            'id_pengguna' => $peminjaman->id_pengguna,
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'tanggal' => now()->toDateString(),
            'aktivitas' => 'Buku Diambil',
            'deskripsi' => 'Buku telah diambil secara fisik oleh mahasiswa.',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Book pickup confirmed!');
    }

    public function exportLaporan(\Illuminate\Http\Request $request)
    {
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        $peminjaman = Peminjaman::with(['user', 'buku' => function($q){
            $q->withTrashed();
        }])
            ->whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun)
            ->orderBy('tanggal_pinjam', 'asc')
            ->get();

        return view('admin.pages.laporan-cetak', compact('peminjaman', 'bulan', 'tahun'));
    }
}
