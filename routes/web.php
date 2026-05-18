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

function getDummyBooks() {
    return collect([
        ['id' => 1, 'book_id' => 'B001', 'judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'genre' => 'Drama', 'status' => 'Tersedia', 'cover' => 'Laskar_Pelangi_Sampul.jpg', 'tahun_terbit' => '2005'],
        ['id' => 2, 'book_id' => 'B002', 'judul' => 'Filosofi Teras', 'penulis' => 'Henry Manampiring', 'genre' => 'Self-Dev', 'status' => 'Dipinjam', 'cover' => 'filosofi_teras.webp', 'tahun_terbit' => '2018'],
        ['id' => 3, 'book_id' => 'B003', 'judul' => 'Akuntansi Dasar', 'penulis' => 'Erlangga', 'genre' => 'Edukasi', 'status' => 'Tersedia', 'cover' => 'Cover_akutansi.jpg', 'tahun_terbit' => '2020'],
        ['id' => 4, 'book_id' => 'B004', 'judul' => 'Hujan', 'penulis' => 'Tere Liye', 'genre' => 'Romance', 'status' => 'Tersedia', 'cover' => 'cover_hujan.jpg', 'tahun_terbit' => '2016'],
        ['id' => 5, 'book_id' => 'B005', 'judul' => 'Bandung After Rain', 'penulis' => 'Viva.co', 'genre' => 'Romance', 'status' => 'Tersedia', 'cover' => 'bandung.after.rain.jpg', 'tahun_terbit' => '2019'],
        ['id' => 6, 'book_id' => 'B006', 'judul' => 'AI For Everyone', 'penulis' => 'Andrew Ng', 'genre' => 'Technology', 'status' => 'Tersedia', 'cover' => 'cover_AI.byerlangga.jpg', 'tahun_terbit' => '2021'],
        ['id' => 7, 'book_id' => 'B007', 'judul' => 'Malioboro at Midnight', 'penulis' => 'Skysphire', 'genre' => 'Romance', 'status' => 'Dipinjam', 'cover' => 'maliboro.cover.jpg', 'tahun_terbit' => '2023'],
        ['id' => 8, 'book_id' => 'B008', 'judul' => 'Bumi', 'penulis' => 'Tere Liye', 'genre' => 'Fantasi', 'status' => 'Tersedia', 'cover' => 'cover_buku_bumi.jpg', 'tahun_terbit' => '2014'],
    ]);
}

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
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');

Route::get('/admin/users', function () {
    return view('admin.pages.datauser'); 
})->name('admin.users');

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

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->forget('user');
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');


// --- USER / MAHASISWA ROUTES ---
Route::get('/katalog', function (Request $request) {
    $semuaBuku = getDummyBooks();

    $query = $request->input('query');
    if ($query) {
        $hasilBuku = $semuaBuku->filter(function ($item) use ($query) {
            return str_contains(strtolower($item['judul']), strtolower($query)) || 
                   str_contains(strtolower($item['penulis']), strtolower($query)) ||
                   str_contains(strtolower($item['genre']), strtolower($query));
        });
    } else {
        $hasilBuku = $semuaBuku;
    }
    return view('user.pages.katalog', ['daftarBuku' => $hasilBuku]);
})->name('katalog');

// Route Search Mahasiswa 
Route::get('/search', function (Request $request) {
    $semuaBuku = getDummyBooks()->map(fn($item) => (object)$item);

    $query = $request->input('query');
    $category = $request->input('category');

    if ($query || $category) {
        $books = $semuaBuku->filter(function ($book) use ($query, $category) {
            $matchQuery = true;
            if ($query) {
                $matchQuery = str_contains(strtolower($book->judul), strtolower($query)) || 
                              str_contains(strtolower($book->penulis), strtolower($query)) ||
                              str_contains(strtolower($book->genre), strtolower($query));
            }
            
            $matchCategory = !$category || strtolower($book->genre) === strtolower($category);

            return $matchQuery && $matchCategory;
        });
    } else {
        $books = collect(); 
    }

    $categories = $semuaBuku->pluck('genre')->unique()->values();

    return view('user.pages.search', compact('books', 'categories'));
})->name('search');

Route::get('/katalog/{id}', function ($id) {
    $buku = getDummyBooks()->firstWhere('id', (int)$id);
    
    if (!$buku) {
        abort(404);
    }

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
    $semuaBuku = getDummyBooks()->map(fn($item) => (object)$item);

    $query = $request->input('query');

        $category = $request->input('category');

    if ($query || $category) {
        $books = $semuaBuku->filter(function ($book) use ($query, $category) {
            $matchQuery = true;
            if ($query) {
                $q = strtolower((string)$query);
                $matchQuery = str_contains(strtolower((string)($book->judul ?? '')), $q) || 
                              str_contains(strtolower((string)($book->book_id ?? '')), $q) ||
                              str_contains(strtolower((string)($book->penulis ?? '')), $q) ||
                              str_contains((string)($book->id ?? ''), $q);
            }
            
            $matchCategory = !$category || strtolower((string)($book->genre ?? '')) === strtolower((string)$category);

            return $matchQuery && $matchCategory;
        });
    } else {
        $books = collect();
    }

    $categories = $semuaBuku->pluck('genre')->unique()->values();

    return view('admin.pages.search', compact('books', 'categories'));
})->name('admin.search');

Route::prefix('admin')->group(function () {

    // DATA BUKU HARUS DI DALAM SINI
    Route::get('/katalog', function () {
        $Buku = getDummyBooks();

        // Mengirimkan variabel $Buku ke view
        return view('admin.pages.katalog-admin', compact('Buku'));
    })->name('admin.katalog');

    // Route edit, update, dan delete tetap seperti sebelumnya
    Route::get('/katalog/{id}/edit', [BukuController::class, 'edit'])->name('admin.edit');
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

// --- ROUTE UNTUK HALAMAN EDIT BUKU ---
Route::get('/admin/buku/{id}/edit', function ($id) {
    $buku = getDummyBooks()->firstWhere('id', (int)$id);

    if (!$buku) {
        abort(404);
    }

    return view('admin.pages.edit-buku', compact('buku'));
})->name('admin.edit_buku'); 

// Route untuk proses updatenya
Route::put('/admin/buku/{id}', function ($id) {
    return redirect()->route('admin.katalog')->with('success', 'Buku berhasil diupdate!');
})->name('admin.update_buku');
