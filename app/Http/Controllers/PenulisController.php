<?php

namespace App\Http\Controllers;

use App\Models\Penulis;
use Illuminate\Http\Request;

class PenulisController extends Controller
{
    /**
     * Display a listing of authors.
     */
    public function index()
    {
        $penulis = Penulis::withCount('buku')->paginate(10);
        return view('admin.pages.penulis', compact('penulis'));
    }

    /**
     * Store a newly created author in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_penulis' => 'required|string|max:255|unique:penulis,nama_penulis',
        ]);

        Penulis::create([
            'nama_penulis' => $request->nama_penulis,
        ]);

        return redirect()->route('admin.penulis.index')->with('success', 'Author successfully added.');
    }

    /**
     * Update the specified author in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_penulis' => 'required|string|max:255|unique:penulis,nama_penulis,' . $id . ',id_penulis',
        ]);

        $penulis = Penulis::findOrFail($id);
        $penulis->update([
            'nama_penulis' => $request->nama_penulis,
        ]);

        return redirect()->route('admin.penulis.index')->with('success', 'Author successfully updated.');
    }

    /**
     * Remove the specified author from storage.
     */
    public function destroy($id)
    {
        $penulis = Penulis::findOrFail($id);

        // Cek apakah ada buku yang masih menggunakan penulis ini
        if ($penulis->buku()->count() > 0) {
            return redirect()->route('admin.penulis.index')->with('error', 'Penulis tidak dapat dihapus karena masih digunakan oleh buku.');
        }

        $penulis->delete();

        return redirect()->route('admin.penulis.index')->with('success', 'Author successfully deleted.');
    }
}
