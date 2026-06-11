<?php

namespace App\Http\Controllers;

use App\Models\Penerbit;
use Illuminate\Http\Request;

class PenerbitController extends Controller
{
    /**
     * Display a listing of publishers.
     */
    public function index()
    {
        $penerbit = Penerbit::withCount('buku')->paginate(10);
        return view('admin.pages.penerbit', compact('penerbit'));
    }

    /**
     * Store a newly created publisher in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_penerbit' => 'required|string|max:255|unique:penerbit,nama_penerbit',
        ]);

        Penerbit::create([
            'nama_penerbit' => $request->nama_penerbit,
        ]);

        return redirect()->route('admin.penerbit.index')->with('success', 'Penerbit berhasil ditambahkan.');
    }

    /**
     * Update the specified publisher in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_penerbit' => 'required|string|max:255|unique:penerbit,nama_penerbit,' . $id . ',id_penerbit',
        ]);

        $penerbit = Penerbit::findOrFail($id);
        $penerbit->update([
            'nama_penerbit' => $request->nama_penerbit,
        ]);

        return redirect()->route('admin.penerbit.index')->with('success', 'Penerbit berhasil diperbarui.');
    }

    /**
     * Remove the specified publisher from storage.
     */
    public function destroy($id)
    {
        $penerbit = Penerbit::findOrFail($id);

        // Cek apakah ada buku yang masih menggunakan penerbit ini
        if ($penerbit->buku()->count() > 0) {
            return redirect()->route('admin.penerbit.index')->with('error', 'Penerbit tidak dapat dihapus karena masih digunakan oleh buku.');
        }

        $penerbit->delete();

        return redirect()->route('admin.penerbit.index')->with('success', 'Penerbit berhasil dihapus.');
    }
}
