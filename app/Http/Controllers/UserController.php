<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Display list of users in admin panel
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $roleFilter = $request->get('role', '');
        
        $query = User::query();
        
        // Search by name, email, or identity number
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('identity_number', 'like', "%$search%");
            });
        }
        
        // Filter by role
        if ($roleFilter && $roleFilter !== 'all') {
            $query->where('role', $roleFilter);
        }
        
        $users = $query->orderBy('id_pengguna', 'desc')->paginate(10);
        return view('admin.pages.users-index', compact('users', 'search', 'roleFilter'));
    }

    // Show form for creating a new user
    public function create()
    {
        return view('admin.pages.users-create');
    }

    // Store new user
    public function store(Request $request)
    {
        if ($request->has('full_name') && !$request->has('nama')) {
            $request->merge(['nama' => $request->full_name]);
        }
        if ($request->role === 'student') {
            $request->merge(['role' => 'mahasiswa']);
        }

        $validated = $request->validate([
            'nama'            => 'required|string|max:255',
            'identity_number' => 'required|regex:/^[0-9]+$/|max:255|unique:users,identity_number',
            'email'           => 'required|email|max:255|unique:users,email',
            'password'        => ['required', 'string', 'min:8', 'max:12', 'regex:/^\S+$/'],
            'role'            => 'required|in:admin,mahasiswa',
            'status'          => 'required|in:active,inactive,suspended',
        ], [
            'identity_number.unique' => 'NIM/NIK sudah terdaftar.',
            'identity_number.regex'  => 'NIM/NIK hanya boleh berisi angka.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    // Update existing user
    public function update(Request $request, $id)
    {
        if ($request->has('full_name') && !$request->has('nama')) {
            $request->merge(['nama' => $request->full_name]);
        }
        if ($request->role === 'student') {
            $request->merge(['role' => 'mahasiswa']);
        }

        $user = User::findOrFail($id);
        $validated = $request->validate([
            'nama'            => 'required|string|max:255',
            'identity_number' => 'required|regex:/^[0-9]+$/|max:255|unique:users,identity_number,' . $id . ',id_pengguna',
            'email'           => 'required|email|max:255|unique:users,email,' . $id . ',id_pengguna',
            'password'        => ['nullable', 'string', 'min:8', 'max:12', 'regex:/^\S+$/'],
            'role'            => 'required|in:admin,mahasiswa',
            'status'          => 'required|in:active,inactive,suspended',
        ], [
            'identity_number.unique' => 'NIM/NIK sudah terdaftar.',
            'identity_number.regex'  => 'NIM/NIK hanya boleh berisi angka.',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.pages.users-edit', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.pages.users-edit', compact('user'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }
}
