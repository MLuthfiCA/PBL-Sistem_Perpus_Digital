<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KategoriController;
use App\Models\Buku;

Route::get('/', function () {
    if (session()->has('user')) {
        if (session('user')['role'] === 'admin') return redirect()->route('admin.katalog');
        return redirect()->route('katalog');
    }
    return redirect('/home');
});

Route::get('/dashboard', function() {
    if (session()->has('user')) {
        if (session('user')['role'] === 'admin') return redirect()->route('admin.katalog');
        return redirect()->route('katalog');
    }
    return redirect('/home');
})->name('dashboard');
Route::get('/profile', [RiwayatController::class, 'tampilkanRiwayat'])->name('profile');

Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');

    // Admin User Management Routes
Route::prefix('admin')->as('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('kategori', KategoriController::class);
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

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

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

    $statusMap = [
        'available' => 'Tersedia',
        'borrowed' => 'Dipinjam',
        'lost' => 'Hilang',
        'maintenance' => 'Perawatan',
    ];

    $bukuQuery = Buku::where('tampil_katalog', true);

    if ($query) {
        $keywords = explode(' ', $query);
        foreach ($keywords as $word) {
            if (!empty(trim($word))) {
                $bukuQuery->where(function ($q) use ($word) {
                    $q->where('judul', 'like', '%' . $word . '%')
                      ->orWhere('penulis', 'like', '%' . $word . '%')
                      ->orWhereHas('kategori', function($k) use ($word) {
                          $k->where('nama_kategori', 'like', '%' . $word . '%');
                      })
                      ->orWhere('lokasi_rak', 'like', '%' . $word . '%');
                });
            }
        }
    }

    $paginator = $bukuQuery->with('kategori')->orderBy('id_buku', 'desc')->paginate(8);

    $paginator->getCollection()->transform(function($buku) use ($statusMap) {
        return [
            'id' => $buku->id_buku,
            'buku_id' => $buku->id_buku,
            'judul' => $buku->judul,
            'penulis' => $buku->penulis,
            'genre' => $buku->kategori ? $buku->kategori->nama_kategori : 'N/A',
            'isbn' => $buku->isbn,
            'penerbit' => $buku->penerbit,
            'tahun_terbit' => $buku->tahun_terbit,
            'cetakan' => $buku->cetakan,
            'bahasa' => $buku->bahasa,
            'status' => $statusMap[$buku->status] ?? $buku->status,
            'cover' => $buku->cover,
            'stok' => $buku->stok,
            'deskripsi' => $buku->deskripsi,
        ];
    });

    return view('user.pages.katalog', ['daftarBuku' => $paginator]);
})->name('katalog');

// Route Search Mahasiswa
Route::get('/search', function (Request $request) {
    $query      = $request->input('query');
    $categories = $request->input('categories', []);   // multi-kategori (array)

    $statusMap = [
        'available'   => 'Tersedia',
        'borrowed'    => 'Dipinjam',
        'lost'        => 'Hilang',
        'maintenance' => 'Perawatan',
    ];

    $bukuQuery = Buku::where('tampil_katalog', true);

    // Keyword search — OR antar kata (sama seperti admin)
    if ($query) {
        $keywords = explode(' ', $query);
        $bukuQuery->where(function ($q) use ($keywords) {
            foreach ($keywords as $word) {
                if (!empty(trim($word))) {
                    $q->orWhere(function ($q2) use ($word) {
                        $q2->where('judul', 'like', '%' . $word . '%')
                           ->orWhere('penulis', 'like', '%' . $word . '%')
                           ->orWhereHas('kategori', function($k) use ($word) {
                               $k->where('nama_kategori', 'like', '%' . $word . '%');
                           })
                           ->orWhere('lokasi_rak', 'like', '%' . $word . '%');
                    });
                }
            }
        });
    }

    // Filter multi-kategori — bisa pilih lebih dari 1
    if (!empty($categories)) {
        $bukuQuery->whereHas('kategori', function($q) use ($categories) {
            $q->whereIn('nama_kategori', $categories);
        });
    }

    $books = $bukuQuery->get()->map(function($buku) use ($statusMap) {
        $buku->status = $statusMap[$buku->status] ?? $buku->status;
        return $buku;
    });

    $allCategories = \App\Models\Kategori::has('buku')->pluck('nama_kategori');

    return view('user.pages.search', compact('books', 'allCategories', 'categories'));
})->name('search');

Route::get('/katalog/{id}', function ($id) {
    $buku = Buku::with('kategori')->findOrFail($id);

    return view('user.pages.detail-buku', [
        'buku' => [
            'id'           => $buku->id_buku,
            'buku_id'      => $buku->id_buku,
            'book_id'      => 'B-' . str_pad($buku->id_buku, 3, '0', STR_PAD_LEFT),
            'judul'        => $buku->judul,
            'penulis'      => $buku->penulis,
            'genre'        => $buku->kategori ? $buku->kategori->nama_kategori : 'N/A',
            'isbn'         => $buku->isbn,
            'penerbit'     => $buku->penerbit,
            'tahun_terbit' => $buku->tahun_terbit,
            'cetakan'      => $buku->cetakan,
            'bahasa'       => $buku->bahasa,
            'lokasi_rak'   => $buku->lokasi_rak,
            'status'       => $buku->status,
            'cover'        => $buku->cover,
            'stok'         => $buku->stok,
            'deskripsi'    => $buku->deskripsi,
        ]
    ]);
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
    
    $statusMap = [
        'available' => 'Tersedia',
        'borrowed' => 'Dipinjam',
        'lost' => 'Hilang',
        'maintenance' => 'Perawatan',
    ];
    
    $bukuQuery = Buku::query();
    
    if ($query) {
        $keywords = explode(' ', $query);
        $bukuQuery->where(function ($q) use ($keywords) {
            foreach ($keywords as $word) {
                if (!empty(trim($word))) {
                    $q->orWhere(function ($q2) use ($word) {
                        $q2->where('judul', 'like', '%' . $word . '%')
                           ->orWhere('penulis', 'like', '%' . $word . '%')
                           ->orWhereHas('kategori', function($k) use ($word) {
                               $k->where('nama_kategori', 'like', '%' . $word . '%');
                           })
                           ->orWhere('isbn', 'like', '%' . $word . '%')
                           ->orWhere('lokasi_rak', 'like', '%' . $word . '%');
                    });
                }
            }
        });
    }
    
    if ($category) {
        $bukuQuery->whereHas('kategori', function($q) use ($category) {
            $q->where('nama_kategori', $category);
        });
    }
    
    $books = $bukuQuery->get()->map(function($buku) use ($statusMap) {
        $buku->status = $statusMap[$buku->status] ?? $buku->status;
        return $buku;
    });
    $categories = \App\Models\Kategori::has('buku')->pluck('nama_kategori');

    return view('admin.pages.search', compact('books', 'categories'));
})->name('admin.search');

Route::prefix('admin')->group(function () {

    // DATA BUKU HARUS DI DALAM SINI (Paginated)
    Route::get('/katalog', function () {
        $statusMap = [
            'available' => 'Tersedia',
            'borrowed' => 'Dipinjam',
            'lost' => 'Hilang',
            'maintenance' => 'Perawatan',
        ];

        $paginator = Buku::orderBy('id_buku', 'desc')->paginate(8);

        $paginator->getCollection()->transform(function($buku) use ($statusMap) {
            return [
                'id' => $buku->id_buku,
                'buku_id' => $buku->id_buku,
                'book_id' => $buku->id_buku,
                'judul' => $buku->judul,
                'penulis' => $buku->penulis,
                'genre' => $buku->kategori ? $buku->kategori->nama_kategori : 'N/A',
                'isbn' => $buku->isbn,
                'penerbit' => $buku->penerbit,
                'tahun_terbit' => $buku->tahun_terbit,
                'cetakan' => $buku->cetakan,
                'bahasa' => $buku->bahasa,
                'status' => $statusMap[$buku->status] ?? $buku->status,
                'cover' => $buku->cover,
                'stok' => $buku->stok,
                'deskripsi' => $buku->deskripsi,
            ];
        });

        return view('admin.pages.katalog-admin', ['Buku' => $paginator]);
    })->name('admin.katalog');

    // Soft-deleted books (trash) routes
    Route::get('/katalog/trash', [BukuController::class, 'trash'])->name('admin.katalog.trash');
    Route::post('/katalog/{id}/restore', [BukuController::class, 'restore'])->name('admin.katalog.restore');

    // Route detail, edit, update, dan delete
    Route::get('/katalog/{id}', [BukuController::class, 'showAdmin'])->name('admin.katalog.detail');
    Route::get('/katalog/{id}/edit', [BukuController::class, 'edit'])->name('admin.edit_buku');
    Route::put('/katalog/{id}', [BukuController::class, 'update'])->name('admin.update');
    Route::delete('/katalog/{id}', [BukuController::class, 'destroy'])->name('admin.delete');
    
    // Route Tambah Buku
    Route::get('/buku/tambah', function () {
        $kategoris = \App\Models\Kategori::all();
        return view('admin.pages.data-buku', compact('kategoris'));
    })->name('admin.buku.create');
    Route::post('/buku/tambah', [BukuController::class, 'store'])->name('admin.buku.store');

    // Route Peminjaman Admin Actions
    Route::post('/peminjaman/{id}/acc', [AdminController::class, 'accPengembalian'])->name('admin.peminjaman.acc');
    Route::post('/peminjaman/{id}/bayar', [AdminController::class, 'bayarDenda'])->name('admin.peminjaman.bayar');

    // Route Export Laporan
    Route::get('/laporan/export', [AdminController::class, 'exportLaporan'])->name('admin.laporan.export');
});


