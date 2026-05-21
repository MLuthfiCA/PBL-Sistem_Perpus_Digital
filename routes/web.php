<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AdminController;
use App\Models\Buku;

Route::get('/', function () {
    if (session()->has('user')) {
        if (session('user')['role'] === 'admin') return redirect()->route('admin.katalog');
        return redirect()->route('katalog');
    }
    return redirect('/home');
});

Route::get('/contact', [HomeController::class, 'contact']);
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/profile', [RiwayatController::class, 'tampilkanRiwayat'])->name('profile');

Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');

    // Admin User Management Routes
Route::prefix('admin')->as('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\UserController::class);
});

// --- GUEST & AUTH ROUTES ---

Route::get('/home', function () {
    return view('user.pages.home');
})->name('home');

Route::get('/login', function () {
    if (session()->has('user')) {
        if (session('user')['role'] === 'admin') return redirect()->route('admin.katalog');
        return redirect()->route('katalog');
    }
    return view('user.pages.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');


Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->forget('user');
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');


// --- USER / MAHASISWA ROUTES ---
Route::get('/katalog', function (Request $request) {
    $query = $request->input('query');
    
    $bukuQuery = Buku::where('tampil_katalog', true);
    
    if ($query) {
        $bukuQuery->where(function ($q) use ($query) {
            $q->where('judul', 'like', '%' . $query . '%')
              ->orWhere('penulis', 'like', '%' . $query . '%')
              ->orWhere('genre', 'like', '%' . $query . '%');
        });
    }
    
    $hasilBuku = $bukuQuery->get();
    $daftarBuku = $hasilBuku->map(function($buku) {
        return [
            'id' => $buku->buku_id,
            'buku_id' => $buku->buku_id,
            'judul' => $buku->judul,
            'penulis' => $buku->penulis,
            'genre' => $buku->genre,
            'isbn' => $buku->isbn,
            'penerbit' => $buku->penerbit,
            'tahun_terbit' => $buku->tahun_terbit,
            'cetakan' => $buku->cetakan,
            'bahasa' => $buku->bahasa,
            'status' => $buku->status,
            'cover' => $buku->cover,
            'stok' => $buku->stok,
            'deskripsi' => $buku->deskripsi,
        ];
    });
    
    return view('user.pages.katalog', ['daftarBuku' => $daftarBuku]);
})->name('katalog');

// Route Search Mahasiswa 
Route::get('/search', function (Request $request) {
    $query = $request->input('query');
    $category = $request->input('category');
    
    $bukuQuery = Buku::where('tampil_katalog', true);
    
    if ($query) {
        $bukuQuery->where(function ($q) use ($query) {
            $q->where('judul', 'like', '%' . $query . '%')
              ->orWhere('penulis', 'like', '%' . $query . '%')
              ->orWhere('genre', 'like', '%' . $query . '%');
        });
    }
    
    if ($category) {
        $bukuQuery->where('genre', $category);
    }
    
    $books = $bukuQuery->get();
    $categories = Buku::where('tampil_katalog', true)->distinct('genre')->pluck('genre');

    return view('user.pages.search', compact('books', 'categories'));
})->name('search');

Route::get('/katalog/{id}', function ($id) {
    $buku = Buku::where('buku_id', $id)->firstOrFail();
    
    return view('user.pages.detail-buku', compact('buku'));
})->name('katalog.detail');

Route::get('/about', function () {
    return view('user.pages.about'); 
})->name('about');



Route::get('/pengajuan', function () {
    if (!session()->has('user')) return redirect('/login');
    return view('user.pages.pengajuan');
})->name('pengajuan');

Route::post('/pengajuan', [BukuController::class, 'storePeminjaman'])->name('pengajuan.store');



// --- AREA ADMIN ---
Route::get('/admin/search', function (Request $request) {
    $query = $request->input('query');
    $category = $request->input('category');
    
    $bukuQuery = Buku::query();
    
    if ($query) {
        $bukuQuery->where(function ($q) use ($query) {
            $q->where('judul', 'like', '%' . $query . '%')
              ->orWhere('penulis', 'like', '%' . $query . '%')
              ->orWhere('genre', 'like', '%' . $query . '%')
              ->orWhere('isbn', 'like', '%' . $query . '%');
        });
    }
    
    if ($category) {
        $bukuQuery->where('genre', $category);
    }
    
    $books = $bukuQuery->get();
    $categories = Buku::distinct('genre')->pluck('genre');

    return view('admin.pages.search', compact('books', 'categories'));
})->name('admin.search');

Route::prefix('admin')->group(function () {

    // DATA BUKU HARUS DI DALAM SINI
    Route::get('/katalog', function () {
        $Buku = Buku::all()->map(function($buku) {
            return [
                'id' => $buku->buku_id,
                'buku_id' => $buku->buku_id,
                'book_id' => $buku->buku_id,
                'judul' => $buku->judul,
                'penulis' => $buku->penulis,
                'genre' => $buku->genre,
                'isbn' => $buku->isbn,
                'penerbit' => $buku->penerbit,
                'tahun_terbit' => $buku->tahun_terbit,
                'cetakan' => $buku->cetakan,
                'bahasa' => $buku->bahasa,
                'status' => $buku->status,
                'cover' => $buku->cover,
                'stok' => $buku->stok,
                'deskripsi' => $buku->deskripsi,
            ];
        })->toArray();

        return view('admin.pages.katalog-admin', compact('Buku'));
    })->name('admin.katalog');

    // Route detail, edit, update, dan delete
    Route::get('/katalog/{id}', [BukuController::class, 'showAdmin'])->name('admin.katalog.detail');
    Route::get('/katalog/{id}/edit', [BukuController::class, 'edit'])->name('admin.edit_buku');
    Route::put('/katalog/{id}', [BukuController::class, 'update'])->name('admin.update');
    Route::delete('/katalog/{id}', [BukuController::class, 'destroy'])->name('admin.delete');
    
    // Route Tambah Buku
    Route::get('/buku/tambah', function () {
        return view('admin.pages.data-buku');
    })->name('admin.buku.create');
    Route::post('/buku/tambah', [BukuController::class, 'store'])->name('admin.buku.store');

    // Route Peminjaman Admin Actions
    Route::post('/peminjaman/{id}/acc', [AdminController::class, 'accPengembalian'])->name('admin.peminjaman.acc');
    Route::post('/peminjaman/{id}/bayar', [AdminController::class, 'bayarDenda'])->name('admin.peminjaman.bayar');
});


