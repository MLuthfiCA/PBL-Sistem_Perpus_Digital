<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Buku;
use App\Models\Peminjaman;


class BukuController extends Controller
{
    public function showAdmin($id)
    {
        $buku = Buku::findOrFail($id);

        return view('admin.pages.detail-buku', [
            'buku' => [
                'id'          => $buku->id_buku,
                'buku_id'     => $buku->id_buku,
                'book_id'     => 'B-' . str_pad($buku->id_buku, 3, '0', STR_PAD_LEFT),
                'judul'       => $buku->judul,
                'penulis'     => $buku->penulis,
                'genre'       => $buku->genre,
                'isbn'        => $buku->isbn,
                'penerbit'    => $buku->penerbit,
                'tahun_terbit'=> $buku->tahun_terbit,
                'cetakan'     => $buku->cetakan,
                'bahasa'      => $buku->bahasa,
                'lokasi_rak'  => $buku->lokasi_rak,
                'status'      => $buku->status,
                'cover'       => $buku->cover,
                'stok'        => $buku->stok,
                'deskripsi'   => $buku->deskripsi,
            ]
        ]);
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $genres = Buku::whereNotNull('genre')->distinct('genre')->pluck('genre');
        
        // Transform ke array untuk view
        return view('admin.pages.edit-buku', [
            'buku' => [
                'id' => $buku->id_buku,
                'buku_id' => $buku->id_buku,
                'judul' => $buku->judul,
                'penulis' => $buku->penulis,
                'genre' => $buku->genre,
                'isbn' => $buku->isbn,
                'penerbit' => $buku->penerbit,
                'tahun_terbit' => $buku->tahun_terbit,
                'cetakan' => $buku->cetakan,
                'bahasa' => $buku->bahasa,
                'lokasi_rak' => $buku->lokasi_rak,
                'status' => $buku->status,
                'cover' => $buku->cover,
                'stok' => $buku->stok,
                'deskripsi' => $buku->deskripsi,
            ],
            'genres' => $genres
        ]);
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        // Validate book data
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:50|unique:buku,isbn,' . $id . ',id_buku',
            'genre' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|string|max:4',
            'cetakan' => 'nullable|string|max:255',
            'bahasa' => 'nullable|string|max:255',
            'lokasi_rak' => 'nullable|string|max:255',
            'stok' => 'nullable|integer|min:0',
            'status' => 'required|in:Tersedia,Dipinjam,Hilang,Perawatan',
            'deskripsi' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
        ]);

        // Handle cover upload if present
        if ($request->hasFile('cover')) {
            $coverName = time() . '_' . $request->file('cover')->getClientOriginalName();
            $request->file('cover')->move(public_path('images'), $coverName);
            $validated['cover'] = $coverName;
        }

        // Update the book record
        $buku->update($validated);

        return redirect()->route('admin.katalog')->with('success', 'Data updated successfully');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('admin.katalog')->with('success', 'Book moved to trash successfully.');
    }

  public function store(Request $request)
{
    $validated = $request->validate([
        'judul' => 'required|string|max:255',
        'penulis' => 'required|string|max:255',
        'isbn' => 'nullable|string|max:50|unique:buku,isbn',
        'genre' => 'required|string|max:255',
        'penerbit' => 'nullable|string|max:255',
        'tahun_terbit' => 'nullable|string|max:4',
        'cetakan' => 'nullable|string|max:255',
        'bahasa' => 'nullable|string|max:255',
        'lokasi_rak' => 'nullable|string|max:255',
        'status' => 'required|in:Tersedia,Dipinjam,Hilang,Perawatan',
        'stok' => 'nullable|integer|min:0',
        'deskripsi' => 'nullable|string',
        'cover' => 'nullable|image|max:2048',
    ]);

    try {

        // Upload cover
        $coverName = null;

        if ($request->hasFile('cover')) {

            $file = $request->file('cover');

            $coverName = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('images'), $coverName);
        }

        // Simpan buku
        Buku::create([
            'judul' => $validated['judul'],
            'penulis' => $validated['penulis'],
            'genre' => $validated['genre'],
            'isbn' => $validated['isbn'] ?? null,
            'penerbit' => $validated['penerbit'] ?? null,
            'tahun_terbit' => $validated['tahun_terbit'] ?? null,
            'cetakan' => $validated['cetakan'] ?? null,
            'bahasa' => $validated['bahasa'] ?? null,
            'lokasi_rak' => $validated['lokasi_rak'] ?? null,
            'status' => $validated['status'],
            'stok' => $validated['stok'] ?? 1,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'cover' => $coverName,
            'tampil_katalog' => 1,
        ]);

        return redirect()
            ->route('admin.katalog')
            ->with('success', 'Book successfully added!');

    } catch (\Exception $e) {

        dd($e->getMessage());
    }
}
    public function index()
{
    $Buku = Buku::all(); // ambil dari database
    return view('admin.pages.katalog-admin', compact('Buku'));
}

    /**
     * Show soft-deleted books (trash)
     */
    public function trash()
    {
        $trashed = Buku::onlyTrashed()->get()->map(function($buku) {
            return [
                'id' => $buku->id_buku,
                'judul' => $buku->judul,
                'penulis' => $buku->penulis,
                'isbn' => $buku->isbn,
                'stok' => $buku->stok,
                'cover' => $buku->cover,
                'deleted_at' => $buku->deleted_at ? $buku->deleted_at->format('d M Y, H:i') : 'N/A',
            ];
        });

        return view('admin.pages.trash-buku', ['trashed' => $trashed]);
    }

    /**
     * Restore a soft-deleted book
     */
    public function restore($id)
    {
        $buku = Buku::withTrashed()->where('id_buku', $id)->first();
        if (!$buku) {
            return redirect()->route('admin.katalog')->with('error', 'Book not found.');
        }

        if ($buku->trashed()) {
            $buku->restore();
            // ensure it's visible in catalog and set status if stock > 0
            $buku->tampil_katalog = 1;
            if ($buku->stok > 0) $buku->status = 'Tersedia';
            $buku->save();
            return redirect()->route('admin.katalog.trash')->with('success', 'Book restored successfully');
        }

        return redirect()->route('admin.katalog.trash')->with('error', 'Book is not deleted');
    }

    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|integer',
            'tanggal_pinjam' => 'required|date',
        ]);

        $user = session('user');
        if (!$user) return redirect('/login');

        try {
            $buku = Buku::findOrFail($request->buku_id);
            $userId = $user['id'] ?? null;
            
            if (!$userId) {
                return back()->with('error', 'User not found. Please log in again.');
            }

            // CEK 1: Apakah user masih memiliki buku yang belum dikembalikan (status: dipinjam)
            $unreturnedBooks = Peminjaman::where('id_pengguna', $userId)
                ->where('status', 'dipinjam')
                ->exists();

            if ($unreturnedBooks) {
                return back()->with('error', 'Anda masih meminjam buku yang belum dikembalikan. Harap kembalikan buku tersebut terlebih dahulu sebelum meminjam buku baru.');
            }

            // CEK 2: Apakah user memiliki denda yang belum lunas
            $unpaidFines = Peminjaman::where('id_pengguna', $userId)
                ->where('status_denda', 'belum_lunas')
                ->exists();

            if ($unpaidFines) {
                return back()->with('error', 'Anda memiliki denda keterlambatan yang belum dilunasi. Harap hubungi admin perpustakaan untuk melunasi denda Anda.');
            }

            $tanggalKembali = date('Y-m-d', strtotime($request->tanggal_pinjam . ' + 7 days'));

            $peminjaman = Peminjaman::create([
                'id_pengguna' => $userId,
                'id_buku' => $request->buku_id,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'batas_kembali' => $tanggalKembali,
                'status' => 'dipinjam',
                'denda' => 0,
                'status_denda' => 'lunas',
            ]);

            // Prevent borrowing when stock is empty
            if ($buku->stok <= 0) {
                return back()->with('error', 'Stok buku telah habis. Tidak bisa meminjam saat ini.');
            }

            $newStock = max(0, $buku->stok - 1);
            $buku->update([
                'stok' => $newStock,
                'status' => $newStock === 0 ? 'Dipinjam' : 'Tersedia',
            ]);

            return redirect()->back()->with('success', 'Borrowing successfully submitted! Please contact the admin.');
        } catch (\Exception $e) {
            Log::error('Borrowing error: ' . $e->getMessage());
            return back()->with('error', 'There is an error: ' . $e->getMessage());
        }
    }
}
