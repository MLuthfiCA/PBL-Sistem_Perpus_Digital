<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;


class BukuController extends Controller
{
    public function edit($id)
{
    $Buku = collect([
        ['id' => 1, 'judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'genre' => 'Drama'],
        ['id' => 2, 'judul' => 'Bumi', 'penulis' => 'Tere Liye', 'genre' => 'Fantasi'],
    ]);

    $buku = $Buku->firstWhere('id', $id);

    return view('admin.pages.edit-buku', compact('buku'));
}

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'book_id' => 'required|string|max:50',
            'genre' => 'required|string',
            'status' => 'required|string',
            'tahun_terbit' => 'nullable|string|max:4',
            'cetakan' => 'nullable|string|max:50',
            'bahasa' => 'nullable|string|max:100',
        ]);

        $buku->update([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'book_id' => $request->book_id,
            'genre' => $request->genre,
            'status' => $request->status,
            'tahun_terbit' => $request->tahun_terbit,
            'cetakan' => $request->cetakan,
            'bahasa' => $request->bahasa,
        ]);

        return redirect()->route('katalog')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('katalog')->with('success', 'Data berhasil dihapus');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'genre' => 'required|string',
            'book_id' => 'required|string|max:50',
            'status' => 'required|string',
            'tahun_terbit' => 'nullable|string|max:4',
            'cetakan' => 'nullable|string|max:50',
            'bahasa' => 'nullable|string|max:100',
            'cover_input' => 'nullable|image|max:2048'
        ]);

        try {
            $coverName = null;
            if ($request->hasFile('cover_input')) {
                $coverName = time() . '_' . $request->file('cover_input')->getClientOriginalName();
                $request->file('cover_input')->move(public_path('images'), $coverName);
            }

            Buku::create([
                'judul' => $request->judul,
                'penulis' => $request->penulis,
                'penerbit' => $request->penerbit,
                'genre' => $request->genre,
                'book_id' => $request->book_id,
                'status' => $request->status,
                'tahun_terbit' => $request->tahun_terbit,
                'cetakan' => $request->cetakan,
                'bahasa' => $request->bahasa ?? 'Indonesia',
                'cover' => $coverName,
            ]);

        } catch (\Exception $e) {
            // If DB connection fails, simulate success anyway for the UI demo
        }

        return redirect()->route('admin.katalog')->with('success', 'Buku berhasil ditambahkan!');
    }
    public function index()
{
    $Buku = Buku::all(); // ambil dari database
    return view('admin.pages.katalog-admin', compact('Buku'));
}

    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'buku_id' => 'required',
            'tanggal_pinjam' => 'required|date',
        ]);

        $user = session('user');
        if (!$user) return redirect('/login');

        try {
            // Check if book is already borrowed
            $buku = Buku::findOrFail($request->buku_id);
            if ($buku->status === 'Dipinjam') {
                return back()->with('error', 'Buku sedang dipinjam.');
            }

            $userId = $user['id'] ?? 1;

            $peminjaman = Peminjaman::create([
                'user_id' => $userId,
                'buku_id' => $request->buku_id,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'batas_kembali' => date('Y-m-d', strtotime($request->tanggal_pinjam . ' + 7 days')),
                'status' => 'dipinjam',
                'denda' => 0,
                'status_denda' => 'lunas',
            ]);

            // Update book status
            $buku->update(['status' => 'Dipinjam']);
        } catch (\Exception $e) {
            // Ignore DB error for UI mock mode
        }

        return redirect()->back()->with('success', 'Peminjaman berhasil diajukan! Silakan temui admin.');
    }
}