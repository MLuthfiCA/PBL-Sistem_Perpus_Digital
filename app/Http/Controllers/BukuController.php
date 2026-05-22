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
        $buku = Buku::where('buku_id', $id)->firstOrFail();

        return view('admin.pages.detail-buku', [
            'buku' => [
                'id' => $buku->buku_id,
                'buku_id' => $buku->buku_id,
                'book_id' => 'B-' . str_pad($buku->buku_id, 3, '0', STR_PAD_LEFT),
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
            ]
        ]);
    }

    public function edit($id)
    {
        $buku = Buku::where('buku_id', $id)->firstOrFail();

        return view('admin.pages.edit-buku', [
            'buku' => [
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
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::where('buku_id', $id)->firstOrFail();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'isbn' => 'required|string|max:50',
            'genre' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|string|max:4',
            'cetakan' => 'nullable|string|max:255',
            'bahasa' => 'nullable|string|max:255',
            'stok' => 'nullable|integer|min:0',
            'status' => 'required|in:Tersedia,Dipinjam',
            'deskripsi' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
        ]);

        // Upload cover baru
        if ($request->hasFile('cover')) {

            $coverName = time() . '_' . $request->file('cover')->getClientOriginalName();

            $request->file('cover')->move(
                public_path('images'),
                $coverName
            );

            $validated['cover'] = $coverName;
        }

        $buku->update($validated);

        return redirect()
            ->route('admin.katalog')
            ->with('success', 'Book updated successfully!');
    }

    public function destroy($id)
    {
        $buku = Buku::where('buku_id', $id)->firstOrFail();

        $buku->delete();

        return redirect()
            ->route('admin.katalog')
            ->with('success', 'Book deleted successfully!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'genre' => 'required|string|max:255',
            'status' => 'required|in:Tersedia,Dipinjam',
            'tahun_terbit' => 'nullable|string|max:4',
            'cetakan' => 'nullable|string|max:50',
            'bahasa' => 'nullable|string|max:100',
            'isbn' => 'required|string|max:50',
            'cover' => 'nullable|image|max:2048',
            'deskripsi' => 'nullable|string',
            'stok' => 'nullable|integer|min:1'
        ]);

        try {

            $coverName = null;

            // Upload cover
            if ($request->hasFile('cover')) {

                $coverName = time() . '_' . $request->file('cover')->getClientOriginalName();

                $request->file('cover')->move(
                    public_path('images'),
                    $coverName
                );
            }

            Buku::create([
                'judul' => $request->judul,
                'penulis' => $request->penulis,
                'penerbit' => $request->penerbit,
                'genre' => $request->genre,
                'status' => $request->status,
                'tahun_terbit' => $request->tahun_terbit,
                'cetakan' => $request->cetakan,
                'bahasa' => $request->bahasa ?? 'Indonesia',
                'isbn' => $request->isbn,
                'kategori_id' => 1,
                'cover' => $coverName,
                'deskripsi' => $request->deskripsi,
                'stok' => $request->stok ?? 1,
                'tampil_katalog' => true,
            ]);

            return redirect()
                ->route('admin.katalog')
                ->with('success', 'Book added successfully!');

        } catch (\Exception $e) {

            Log::error('Store buku error: ' . $e->getMessage());

            return back()->with(
                'error',
                'Terjadi kesalahan: ' . $e->getMessage()
            );
        }
    }

    public function index()
    {
        $Buku = Buku::all();

        return view('admin.pages.katalog-admin', compact('Buku'));
    }

    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|integer',
            'tanggal_pinjam' => 'required|date',
        ]);

        $user = session('user');

        if (!$user) {
            return redirect('/login');
        }

        try {

            $buku = Buku::findOrFail($request->buku_id);

            $userId = $user['id'] ?? null;

            if (!$userId) {
                return back()->with(
                    'error',
                    'User tidak ditemukan. Silakan login kembali.'
                );
            }

            $tanggalKembali = date(
                'Y-m-d',
                strtotime($request->tanggal_pinjam . ' + 7 days')
            );

            Peminjaman::create([
                'user_id' => $userId,
                'buku_id' => $request->buku_id,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'batas_kembali' => $tanggalKembali,
                'status' => 'dipinjam',
                'denda' => 0,
                'status_denda' => 'lunas',
            ]);

            // Kurangi stok buku
            if ($buku->stok > 0) {

                $buku->update([
                    'stok' => $buku->stok - 1,
                ]);
            }

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Borrow request submitted. Please contact an administrator.'
                );

        } catch (\Exception $e) {

            Log::error('Peminjaman error: ' . $e->getMessage());

            return back()->with(
                'error',
                'Terjadi kesalahan: ' . $e->getMessage()
            );
        }
    }
}