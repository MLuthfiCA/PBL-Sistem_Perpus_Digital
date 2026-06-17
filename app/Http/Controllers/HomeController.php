<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\User;
use App\Models\Kategori;

class HomeController extends Controller
{
    public function index()
    {
        $totalBuku    = Buku::count();
        $totalUsers   = User::where('role', '!=', 'admin')->count();
        $bukuTersedia = Buku::where('status', 'Tersedia')->count();
        $pctTersedia  = $totalBuku > 0 ? round(($bukuTersedia / $totalBuku) * 100) : 0;

        $genreStats = Kategori::withCount('buku')
            ->orderByDesc('buku_count')
            ->get()
            ->filter(fn($k) => $k->buku_count > 0)
            ->map(function ($k) use ($totalBuku) {
                return [
                    'name'    => $k->nama_kategori,
                    'count'   => $k->buku_count,
                    'percent' => $totalBuku > 0 ? round(($k->buku_count / $totalBuku) * 100) : 0,
                ];
            });

        return view('user.pages.home', compact('totalBuku', 'totalUsers', 'pctTersedia', 'genreStats'));
    }

    public function contact()
    {
        return view('user.pages.contact');
    }
}
?>