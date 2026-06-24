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
use App\Http\Controllers\PenulisController;
use App\Http\Controllers\PenerbitController;
use App\Models\Buku;

// Temporary diagnostic code
try {
    $diag = [];
    $diag['tables_in_db'] = \Illuminate\Support\Facades\DB::select("SELECT name, type FROM sqlite_master");
    
    // Check table 'penulis' details
    try {
        $diag['penulis_columns'] = \Illuminate\Support\Facades\Schema::getColumnListing('penulis');
        $diag['penulis_count'] = \Illuminate\Support\Facades\DB::table('penulis')->count();
    } catch (\Exception $e) {
        $diag['penulis_error'] = $e->getMessage();
    }
    
    // Check table 'buku' columns
    try {
        $diag['buku_columns'] = \Illuminate\Support\Facades\Schema::getColumnListing('buku');
    } catch (\Exception $e) {
        $diag['buku_error'] = $e->getMessage();
    }

    // Run git commands
    $diag['git_status'] = shell_exec("git status 2>&1");
    $diag['git_diff'] = shell_exec("git diff 2>&1");

    // Run database updates for book covers
    try {
        \Illuminate\Support\Facades\DB::table('buku')->where('judul', 'Laskar Pelangi')->update(['cover' => 'Laskar_pelangi_sampul.jpg']);
        \Illuminate\Support\Facades\DB::table('buku')->where('judul', 'Bumi')->update(['cover' => 'cover_buku_bumi.jpg']);
        \Illuminate\Support\Facades\DB::table('buku')->where('judul', 'Filosofi Teras')->update(['cover' => 'filosofi_teras.webp']);
    } catch (\Exception $e) {
        $diag['db_update_error'] = $e->getMessage();
    }

    file_put_contents(storage_path('logs/debug_out.json'), json_encode($diag, JSON_PRETTY_PRINT));
} catch (\Exception $e) {
    file_put_contents(storage_path('logs/debug_out.json'), json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT));
}

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
Route::get('/admin/manage-data', [AdminController::class, 'manageData'])->name('admin.manage-data');

    // Admin User Management Routes
Route::prefix('admin')->as('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('penulis', PenulisController::class);
    Route::resource('penerbit', PenerbitController::class);
});

// --- GUEST & AUTH ROUTES ---

Route::get('/run-manual-migration', function () {
    $logs = [];
    try {
        $logs[] = "Checking table penulis...";
        if (!\Illuminate\Support\Facades\Schema::hasTable('penulis')) {
            $logs[] = "Creating table penulis...";
            \Illuminate\Support\Facades\Schema::create('penulis', function ($table) {
                $table->id('id_penulis');
                $table->string('nama_penulis')->unique();
                $table->timestamps();
            });
            $logs[] = "Table penulis created.";
        } else {
            $logs[] = "Table penulis already exists.";
        }

        $logs[] = "Checking table penerbit...";
        if (!\Illuminate\Support\Facades\Schema::hasTable('penerbit')) {
            $logs[] = "Creating table penerbit...";
            \Illuminate\Support\Facades\Schema::create('penerbit', function ($table) {
                $table->id('id_penerbit');
                $table->string('nama_penerbit')->unique();
                $table->timestamps();
            });
            $logs[] = "Table penerbit created.";
        } else {
            $logs[] = "Table penerbit already exists.";
        }

        $logs[] = "Checking columns in table buku...";
        \Illuminate\Support\Facades\Schema::table('buku', function ($table) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('buku', 'id_penulis')) {
                $table->unsignedBigInteger('id_penulis')->nullable();
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('buku', 'id_penerbit')) {
                $table->unsignedBigInteger('id_penerbit')->nullable();
            }
        });
        $logs[] = "Columns id_penulis and id_penerbit verified/added.";

        $logs[] = "Migrating penulis data...";
        if (\Illuminate\Support\Facades\Schema::hasColumn('buku', 'penulis')) {
            $books = \Illuminate\Support\Facades\DB::table('buku')->whereNotNull('penulis')->where('penulis', '!=', '')->get();
            foreach ($books as $book) {
                if (empty($book->id_penulis)) {
                    $penulisId = \Illuminate\Support\Facades\DB::table('penulis')->where('nama_penulis', $book->penulis)->value('id_penulis');
                    if (!$penulisId) {
                        $penulisId = \Illuminate\Support\Facades\DB::table('penulis')->insertGetId([
                            'nama_penulis' => $book->penulis,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    \Illuminate\Support\Facades\DB::table('buku')->where('id_buku', $book->id_buku)->update(['id_penulis' => $penulisId]);
                }
            }
        }
        $logs[] = "Penulis data migrated.";

        $logs[] = "Migrating penerbit data...";
        if (\Illuminate\Support\Facades\Schema::hasColumn('buku', 'penerbit')) {
            $books = \Illuminate\Support\Facades\DB::table('buku')->whereNotNull('penerbit')->where('penerbit', '!=', '')->get();
            foreach ($books as $book) {
                if (empty($book->id_penerbit)) {
                    $penerbitId = \Illuminate\Support\Facades\DB::table('penerbit')->where('nama_penerbit', $book->penerbit)->value('id_penerbit');
                    if (!$penerbitId) {
                        $penerbitId = \Illuminate\Support\Facades\DB::table('penerbit')->insertGetId([
                            'nama_penerbit' => $book->penerbit,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    \Illuminate\Support\Facades\DB::table('buku')->where('id_buku', $book->id_buku)->update(['id_penerbit' => $penerbitId]);
                }
            }
        }
        $logs[] = "Penerbit data migrated.";

        $logs[] = "Dropping columns penulis and penerbit from table buku...";
        \Illuminate\Support\Facades\Schema::table('buku', function ($table) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('buku', 'penulis')) {
                $table->dropColumn('penulis');
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('buku', 'penerbit')) {
                $table->dropColumn('penerbit');
            }
        });
        $logs[] = "Columns dropped. Migration completed successfully!";

        file_put_contents(storage_path('logs/migration_logs.json'), json_encode($logs, JSON_PRETTY_PRINT));
        return response()->json(['status' => 'success', 'logs' => $logs]);

    } catch (\Exception $e) {
        $logs[] = "Error: " . $e->getMessage();
        file_put_contents(storage_path('logs/migration_logs.json'), json_encode($logs, JSON_PRETTY_PRINT));
        return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'logs' => $logs]);
    }
});

Route::get('/home', function () {
    $totalBuku = \App\Models\Buku::count();
    $totalMember = \App\Models\User::where('role', 'mahasiswa')->count();
    $tersediaBuku = \App\Models\Buku::where('status', 'Tersedia')->where('stok', '>', 0)->count();
    $availablePercent = $totalBuku > 0 ? round(($tersediaBuku / $totalBuku) * 100) : 0;

    $trendingCategories = \App\Models\Kategori::withCount('buku')
                            ->orderByDesc('buku_count')
                            ->limit(6)
                            ->pluck('nama_kategori');

    // Genre stats untuk chart
    $genreStats = \App\Models\Kategori::withCount('buku')
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

    return view('user.pages.home', compact('totalBuku', 'totalMember', 'availablePercent', 'trendingCategories', 'genreStats'));
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
    $request->session()->flush();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->forget('user');
    $request->session()->flush();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
});


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
                      ->orWhereHas('penulis', function($p) use ($word) {
                          $p->where('nama_penulis', 'like', '%' . $word . '%');
                      })
                      ->orWhereHas('kategori', function($k) use ($word) {
                          $k->where('nama_kategori', 'like', '%' . $word . '%');
                      })
                      ->orWhere('lokasi_rak', 'like', '%' . $word . '%');
                });
            }
        }
    }

    $paginator = $bukuQuery->with('kategori', 'penulis', 'penerbit')->orderBy('id_buku', 'desc')->paginate(8);

    $paginator->getCollection()->transform(function($buku) use ($statusMap) {
        return [
            'id' => $buku->id_buku,
            'buku_id' => $buku->id_buku,
            'judul' => $buku->judul,
            'penulis' => $buku->penulis->isNotEmpty() ? $buku->penulis->pluck('nama_penulis')->implode(', ') : 'N/A',
            'genre' => $buku->kategori ? $buku->kategori->nama_kategori : 'N/A',
            'isbn' => $buku->isbn,
            'penerbit' => $buku->penerbit ? $buku->penerbit->nama_penerbit : 'N/A',
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
                           ->orWhereHas('penulis', function($p) use ($word) {
                               $p->where('nama_penulis', 'like', '%' . $word . '%');
                           })
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

    $books = $bukuQuery->with('kategori', 'penulis', 'penerbit')->take(4)->get()->map(function($buku) use ($statusMap) {
        $buku->status = $statusMap[$buku->status] ?? $buku->status;
        $buku->penulis_nama = $buku->penulis->isNotEmpty() ? $buku->penulis->pluck('nama_penulis')->implode(', ') : 'N/A';
        $buku->penerbit_nama = $buku->penerbit ? $buku->penerbit->nama_penerbit : 'N/A';
        return $buku;
    });

    $allCategories = \App\Models\Kategori::has('buku')->pluck('nama_kategori');

    return view('user.pages.search', compact('books', 'allCategories', 'categories'));
})->name('search');

Route::get('/katalog/{id}', function ($id) {
    $buku = Buku::with('kategori', 'penulis', 'penerbit')->findOrFail($id);

    return view('user.pages.detail-buku', [
        'buku' => [
            'id'           => $buku->id_buku,
            'buku_id'      => $buku->id_buku,
            'book_id'      => 'B-' . str_pad($buku->id_buku, 3, '0', STR_PAD_LEFT),
            'judul'        => $buku->judul,
            'penulis'      => $buku->penulis->isNotEmpty() ? $buku->penulis->pluck('nama_penulis')->implode(', ') : 'N/A',
            'genre'        => $buku->kategori ? $buku->kategori->nama_kategori : 'N/A',
            'isbn'         => $buku->isbn,
            'penerbit'     => $buku->penerbit ? $buku->penerbit->nama_penerbit : 'N/A',
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



Route::get('/api/hitung-kembali', function (Request $request) {
    $request->validate(['tanggal_pinjam' => 'required|date']);
    $res = \App\Helpers\HolidayHelper::calculateReturnDate($request->tanggal_pinjam);
    return response()->json($res);
});

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
                           ->orWhereHas('penulis', function($p) use ($word) {
                               $p->where('nama_penulis', 'like', '%' . $word . '%');
                           })
                           ->orWhereHas('kategori', function($k) use ($word) {
                               $k->where('nama_kategori', 'like', '%' . $word . '%');
                           })
                           ->orWhereHas('penerbit', function($pb) use ($word) {
                               $pb->where('nama_penerbit', 'like', '%' . $word . '%');
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
    
    $books = $bukuQuery->with('kategori', 'penulis', 'penerbit')->take(4)->get()->map(function($buku) use ($statusMap) {
        $buku->status = $statusMap[$buku->status] ?? $buku->status;
        $buku->penulis_nama = $buku->penulis->isNotEmpty() ? $buku->penulis->pluck('nama_penulis')->implode(', ') : 'N/A';
        $buku->penerbit_nama = $buku->penerbit ? $buku->penerbit->nama_penerbit : 'N/A';
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

        $paginator = Buku::with('kategori', 'penulis', 'penerbit')->orderBy('id_buku', 'desc')->paginate(8);

        $paginator->getCollection()->transform(function($buku) use ($statusMap) {
            return [
                'id'          => $buku->id_buku,
                'buku_id'     => $buku->id_buku,
                'book_id'     => $buku->id_buku,
                'judul'       => $buku->judul,
                'penulis'     => $buku->penulis->isNotEmpty() ? $buku->penulis->pluck('nama_penulis')->implode(', ') : 'N/A',
                'genre'       => $buku->kategori ? $buku->kategori->nama_kategori : 'N/A',
                'isbn'        => $buku->isbn,
                'penerbit'    => $buku->penerbit ? $buku->penerbit->nama_penerbit : 'N/A',
                'tahun_terbit'=> $buku->tahun_terbit,
                'cetakan'     => $buku->cetakan,
                'bahasa'      => $buku->bahasa,
                'status'      => $statusMap[$buku->status] ?? $buku->status,
                'cover'       => $buku->cover,
                'stok'        => $buku->stok,
                'deskripsi'   => $buku->deskripsi,
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
        $penulis   = \App\Models\Penulis::orderBy('nama_penulis')->get();
        $penerbit  = \App\Models\Penerbit::orderBy('nama_penerbit')->get();
        return view('admin.pages.data-buku', compact('kategoris', 'penulis', 'penerbit'));
    })->name('admin.buku.create');
    Route::post('/buku/tambah', [BukuController::class, 'store'])->name('admin.buku.store');

    // Route Peminjaman Admin Actions
    Route::post('/peminjaman/{id}/acc-ambil', [AdminController::class, 'accPengambilan'])->name('admin.peminjaman.acc_ambil');
    Route::post('/peminjaman/{id}/acc', [AdminController::class, 'accPengembalian'])->name('admin.peminjaman.acc');
    Route::post('/peminjaman/{id}/bayar', [AdminController::class, 'bayarDenda'])->name('admin.peminjaman.bayar');

    // Route Export Laporan
    Route::get('/laporan/export', [AdminController::class, 'exportLaporan'])->name('admin.laporan.export');

    // Route: check if a book ID is already taken (for real-time validation)
    Route::get('/check-buku-id', function (Request $request) {
        $id      = (int) $request->query('id');
        $current = (int) $request->query('current', 0);

        if ($id <= 0) {
            return response()->json(['taken' => false]);
        }

        $taken = Buku::where('id_buku', $id)
                     ->where('id_buku', '!=', $current)
                     ->exists();

        return response()->json(['taken' => $taken]);
    })->name('admin.check_buku_id');
});


