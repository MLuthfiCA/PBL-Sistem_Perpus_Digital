<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        if (session()->has('user')) {
            if (session('user')['role'] === 'admin') {
                return redirect()->route('admin.katalog');
            }
            return redirect()->route('katalog');
        }

        return view('user.pages.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'nim' => 'required|string|max:100|unique:users,identity_number',
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'identity_number' => $request->nim,
            'role' => 'mahasiswa',
            'status' => 'active',
        ]);

        return redirect()->route('login')->with('success', 'Registration successful. Please login to your account.');
    }
}
