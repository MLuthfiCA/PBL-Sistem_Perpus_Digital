<?php

namespace App\Http\Controllers;

use App\Models\Penerbit;
use Illuminate\Http\Request;

class PenerbitController extends Controller
{
    /**
     * Display a listing of publishers.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        
        $query = Penerbit::withCount('buku');
        
        if ($search) {
            $query->where('nama_penerbit', 'like', "%$search%");
        }
        
        $penerbit = $query->orderBy('id_penerbit', 'desc')->paginate(10)->appends(['search' => $search]);
        return view('admin.pages.penerbit', compact('penerbit', 'search'));
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

        return redirect()->route('admin.penerbit.index')->with('success', 'Publisher successfully added.');
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

        return redirect()->route('admin.penerbit.index')->with('success', 'Publisher successfully updated.');
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

        return redirect()->route('admin.penerbit.index')->with('success', 'Publisher successfully deleted.');
    }
}
