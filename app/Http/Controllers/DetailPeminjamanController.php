<?php

namespace App\Http\Controllers;

use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;

class DetailPeminjamanController extends Controller
{
    // Menampilkan semua detail dari suatu peminjaman
    public function index()
    {
        // Mengambil detail beserta data peminjaman dan buku (Eager Loading)
        $details = DetailPeminjaman::with(['peminjaman', 'buku'])->get();
        return view('detail_peminjaman.index', compact('details'));
    }

    // Menampilkan form tambah detail
    public function create()
    {
        $peminjamans = Peminjaman::all();
        $bukus = Buku::all();
        return view('detail_peminjaman.create', compact('peminjamans', 'bukus'));
    }

    // Menyimpan data detail peminjaman baru
    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamans,id',
            'buku_id'       => 'required|exists:bukus,id',
            'jumlah'        => 'required|integer|min:1'
        ]);

        DetailPeminjaman::create($request->all());

        return redirect()->route('detail-peminjaman.index')
                         ->with('success', 'Detail peminjaman berhasil ditambahkan!'); [cite: 39]
    }

    // Menampilkan form edit detail
    public function edit(DetailPeminjaman $detailPeminjaman)
    {
        $peminjamans = Peminjaman::all();
        $bukus = Buku::all();
        return view('detail_peminjaman.edit', compact('detailPeminjaman', 'peminjamans', 'bukus'));
    }

    // Mengubah data detail (misal jumlah buku yang dipinjam)
    public function update(Request $request, DetailPeminjaman $detailPeminjaman)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamans,id',
            'buku_id'       => 'required|exists:bukus,id',
            'jumlah'        => 'required|integer|min:1'
        ]);

        $detailPeminjaman->update($request->all());

        return redirect()->route('detail-peminjaman.index')
                         ->with('success', 'Detail peminjaman berhasil diperbarui!'); [cite: 39]
    }

    // Menghapus item dari detail peminjaman
    public function destroy(DetailPeminjaman $detailPeminjaman)
    {
        $detailPeminjaman->delete();

        return redirect()->route('detail-peminjaman.index')
                         ->with('success', 'Detail peminjaman berhasil dihapus!'); [cite: 39]
    }
}
