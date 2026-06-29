<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Buku;
use App\Models\Penulis;
use App\Models\Penerbit;
use App\Models\Peminjaman;


class BukuController extends Controller
{
    public function showAdmin($id)
    {
        $buku = Buku::with('kategori', 'penulis', 'penerbit')->findOrFail($id);

        return view('admin.pages.detail-buku', [
            'buku' => [
                'id'          => $buku->id_buku,
                'buku_id'     => $buku->id_buku,
                'book_id'     => 'B-' . str_pad($buku->id_buku, 3, '0', STR_PAD_LEFT),
                'judul'       => $buku->judul,
                'penulis'     => $buku->penulis->isNotEmpty() ? $buku->penulis->pluck('nama_penulis')->implode(', ') : 'N/A',
                'id_kategori' => $buku->id_kategori,
                'genre'       => $buku->kategori ? $buku->kategori->nama_kategori : 'N/A',
                'isbn'        => $buku->isbn,
                'penerbit'    => $buku->penerbit ? $buku->penerbit->nama_penerbit : 'N/A',
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
        $buku = Buku::with('kategori', 'penulis', 'penerbit')->findOrFail($id);
        $kategoris = \App\Models\Kategori::all();
        $penulis   = Penulis::orderBy('nama_penulis')->get();
        $penerbit  = Penerbit::orderBy('nama_penerbit')->get();

        return view('admin.pages.edit-buku', [
            'buku' => [
                'id'          => $buku->id_buku,
                'buku_id'     => $buku->id_buku,
                'judul'       => $buku->judul,
                'id_penulis'  => $buku->penulis->pluck('id_penulis')->toArray(),
                'id_penerbit' => $buku->id_penerbit,
                'penulis'     => $buku->penulis->isNotEmpty() ? $buku->penulis->pluck('nama_penulis')->implode(', ') : 'N/A',
                'id_kategori' => $buku->id_kategori,
                'genre'       => $buku->kategori ? $buku->kategori->nama_kategori : 'N/A',
                'isbn'        => $buku->isbn,
                'penerbit'    => $buku->penerbit ? $buku->penerbit->nama_penerbit : 'N/A',
                'tahun_terbit'=> $buku->tahun_terbit,
                'cetakan'     => $buku->cetakan,
                'bahasa'      => $buku->bahasa,
                'lokasi_rak'  => $buku->lokasi_rak,
                'status'      => $buku->status,
                'cover'       => $buku->cover,
                'stok'        => $buku->stok,
                'deskripsi'   => $buku->deskripsi,
            ],
            'kategoris' => $kategoris,
            'penulis'   => $penulis,
            'penerbit'  => $penerbit,
        ]);
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        // Validate book data
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'id_penulis'   => 'required|array',
            'id_penulis.*' => 'exists:penulis,id_penulis',
            'isbn'         => 'nullable|string|max:17|unique:buku,isbn,' . $id . ',id_buku',
            'id_kategori'  => 'required|exists:kategori,id_kategori',
            'id_penerbit'  => 'nullable|exists:penerbit,id_penerbit',
            'tahun_terbit' => 'nullable|string|max:4',
            'cetakan'      => 'nullable|string|max:255',
            'bahasa'       => 'nullable|string|max:255',
            'lokasi_rak'   => 'nullable|string|max:255',
            'stok'         => 'nullable|integer|min:0',
            'status'       => 'required|in:Tersedia,Dipinjam,Hilang,Perawatan',
            'deskripsi'    => 'nullable|string',
            'cover'        => 'nullable|image|max:2048',
        ], [
            'isbn.max' => 'ISBN may not be greater than 17 characters.',
        ]);

        // Handle cover upload if present
        if ($request->hasFile('cover')) {
            $coverName = time() . '_' . $request->file('cover')->getClientOriginalName();
            $request->file('cover')->move(public_path('images'), $coverName);
            $validated['cover'] = $coverName;
        }

        // Update the book record (excluding id_penulis from direct update if it was still in validated array, but it's safe since it's not fillable anymore)
        $buku->update($validated);
        
        // Sync the pivot table
        $buku->penulis()->sync($validated['id_penulis']);

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
            'judul'        => 'required|string|max:255',
            'id_penulis'   => 'required|array',
            'id_penulis.*' => 'exists:penulis,id_penulis',
            'isbn'         => 'nullable|string|max:17|unique:buku,isbn',
            'id_kategori'  => 'required|exists:kategori,id_kategori',
            'id_penerbit'  => 'nullable|exists:penerbit,id_penerbit',
            'tahun_terbit' => 'nullable|string|max:4',
            'cetakan'      => 'nullable|string|max:255',
            'bahasa'       => 'nullable|string|max:255',
            'lokasi_rak'   => 'nullable|string|max:255',
            'status'       => 'required|in:Tersedia,Dipinjam,Hilang,Perawatan',
            'stok'         => 'nullable|integer|min:0',
            'deskripsi'    => 'nullable|string',
            'cover'        => 'nullable|image|max:2048',
        ], [
            'isbn.max' => 'ISBN may not be greater than 17 characters.',
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
            $buku = Buku::create([
                'judul'        => $validated['judul'],
                'id_kategori'  => $validated['id_kategori'],
                'isbn'         => $validated['isbn'] ?? null,
                'id_penerbit'  => $validated['id_penerbit'] ?? null,
                'tahun_terbit' => $validated['tahun_terbit'] ?? null,
                'cetakan'      => $validated['cetakan'] ?? null,
                'bahasa'       => $validated['bahasa'] ?? null,
                'lokasi_rak'   => $validated['lokasi_rak'] ?? null,
                'status'       => $validated['status'],
                'stok'         => $validated['stok'] ?? 1,
                'deskripsi'    => $validated['deskripsi'] ?? null,
                'cover'        => $coverName,
                'tampil_katalog' => 1,
            ]);

            // Sync the pivot table
            $buku->penulis()->sync($validated['id_penulis']);

            return redirect()
                ->route('admin.katalog')
                ->with('success', 'Book successfully added!');

        } catch (\Exception $e) {

            dd($e->getMessage());
        }
    }

    public function index()
    {
        $Buku = Buku::with('penulis', 'penerbit')->get();
        return view('admin.pages.katalog-admin', compact('Buku'));
    }

    /**
     * Show soft-deleted books (trash)
     */
    public function trash()
    {
        $trashed = Buku::onlyTrashed()->with('penulis', 'penerbit')->get()->map(function($buku) {
            return [
                'id'         => $buku->id_buku,
                'judul'      => $buku->judul,
                'penulis'    => $buku->penulis->isNotEmpty() ? $buku->penulis->pluck('nama_penulis')->implode(', ') : 'N/A',
                'isbn'       => $buku->isbn,
                'stok'       => $buku->stok,
                'cover'      => $buku->cover,
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

    /**
     * Permanently delete a book and preserve its title in history
     */
    public function forceDelete($id)
    {
        $buku = Buku::withTrashed()->where('id_buku', $id)->first();
        if (!$buku) {
            return redirect()->route('admin.katalog.trash')->with('error', 'Book not found.');
        }

        // Save snapshot of the book title to all related peminjaman records
        Peminjaman::where('id_buku', $buku->id_buku)->update([
            'snapshot_judul_buku' => $buku->judul
        ]);

        $buku->forceDelete();

        return redirect()->route('admin.katalog.trash')->with('success', 'Book permanently deleted!');
    }

    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'buku_id'       => 'required|integer',
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

            // CEK 1: dihapus sesuai permintaan agar bisa pinjam lebih dari 1 buku


            // CEK 2: Apakah user memiliki denda yang belum lunas
            $unpaidFines = Peminjaman::where('id_pengguna', $userId)
                ->where('status_denda', 'belum_lunas')
                ->exists();

            if ($unpaidFines) {
                return back()->with('error', 'You have unpaid late fines. Please contact the library administrator to settle your fines.');
            }

            // Prevent borrowing when book is not available or stock is empty
            if ($buku->status !== 'Tersedia' || $buku->stok <= 0) {
                return back()->with('error', 'This book is currently unavailable for borrowing.');
            }

            $calc = \App\Helpers\HolidayHelper::calculateReturnDate($request->tanggal_pinjam);
            $tanggalKembali = $calc['return_date'];

            $peminjaman = Peminjaman::create([
                'id_pengguna'   => $userId,
                'id_buku'       => $request->buku_id,
                'tanggal_pinjam'=> $request->tanggal_pinjam,
                'batas_kembali' => $tanggalKembali,
                'status'        => 'dipinjam',
                'denda'         => 0,
                'status_denda'  => 'lunas',
            ]);

            $newStock = max(0, $buku->stok - 1);
            $buku->update([
                'stok'   => $newStock,
                'status' => $newStock === 0 ? 'Dipinjam' : 'Tersedia',
            ]);

            // Catat Riwayat Peminjaman
            \App\Models\Riwayat::create([
                'id_pengguna' => $userId,
                'id_peminjaman' => $peminjaman->id,
                'tanggal' => now()->toDateString(),
                'aktivitas' => 'Pengajuan Peminjaman',
                'deskripsi' => 'User mengajukan peminjaman buku: ' . $buku->judul,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('profile')->with('success', 'Borrowing successfully submitted! Please contact the admin.');
        } catch (\Exception $e) {
            Log::error('Borrowing error: ' . $e->getMessage());
            return back()->with('error', 'There is an error: ' . $e->getMessage());
        }
    }
}
