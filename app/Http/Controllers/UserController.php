<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Display list of users in admin panel
    public function index()
    {
        $users = User::all();
        return view('admin.pages.users.index', compact('users'));
    }

    // Store new user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'identity_number' => 'required|string|max:255|unique:users,identity_number',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,student',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    // Update existing user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'identity_number' => 'required|string|max:255|unique:users,identity_number,' . $id . ',user_id',
            'username' => 'required|string|max:255|unique:users,username,' . $id . ',user_id',
            'email' => 'required|email|max:255|unique:users,email,' . $id . ',user_id',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,student',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.pages.users.edit', compact('user'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }
}
