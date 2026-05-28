<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
                'status' => $buku->status,
                'cover' => $buku->cover,
                'stok' => $buku->stok,
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        // Validate book data
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:50',
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

        // Handle cover upload if present
        if ($request->hasFile('cover')) {
            $coverName = time() . '_' . $request->file('cover')->getClientOriginalName();
            $request->file('cover')->move(public_path('images'), $coverName);
            $validated['cover'] = $coverName;
        }

        // Update the book record
        $buku->update($validated);

        return redirect()->route('admin.katalog')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('katalog')->with('success', 'Data berhasil dihapus');
    }

  public function store(Request $request)
{
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
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'genre' => $request->genre,
            'isbn' => $request->isbn,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'cetakan' => $request->cetakan,
            'bahasa' => $request->bahasa,
            'status' => $request->status,
            'stok' => $request->stok ?? 1,
            'deskripsi' => $request->deskripsi,
            'cover' => $coverName,
            'id_kategori' => 1,
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
                return back()->with('error', 'User tidak ditemukan. Silakan login kembali.');
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

            // Update book stock and status if needed
            if ($buku->stok > 0) {
                $buku->update([
                    'stok' => $buku->stok - 1,
                ]);
            }

            return redirect()->back()->with('success', 'Peminjaman berhasil diajukan! Silakan temui admin.');
        } catch (\Exception $e) {
            \Log::error('Peminjaman error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}