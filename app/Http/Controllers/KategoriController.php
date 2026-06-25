<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        
        $query = Kategori::query();
        
        if ($search) {
            $query->where('nama_kategori', 'like', "%$search%");
        }
        
        $kategoris = $query->orderBy('id_kategori', 'desc')->paginate(10)->appends(['search' => $search]);
        return view('admin.pages.kategori', compact('kategoris', 'search'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Category successfully added.');
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori,' . $id . ',id_kategori',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Category successfully updated.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        
        // Cek apakah ada buku yang masih menggunakan kategori ini
        if ($kategori->buku()->count() > 0) {
            return redirect()->route('admin.kategori.index')->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh buku.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Category successfully deleted.');
    }
}
