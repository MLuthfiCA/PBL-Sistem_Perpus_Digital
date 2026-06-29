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
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'string', 'min:8', 'max:12', 'regex:/^\S+$/'],
            'nim'      => [
                'required',
                'regex:/^[0-9]{1,12}$/',
                'unique:users,identity_number',
            ],

        ], [
            'nama.required'     => 'Nama lengkap wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.required' => 'Password is required.',
            'password.min'      => 'Password must be at least 8 characters.',
            'password.max'      => 'Password must not exceed 12 characters.',
            'password.regex'    => 'Password must not contain spaces.',
            'nim.required'      => 'NIM (Student ID) wajib diisi.',
            'nim.regex'         => 'NIM hanya boleh angka dan maksimal 12 digit.',
            'nim.unique'        => 'NIM sudah terdaftar.',
        ]);

        User::create([
            'nama'            => $request->nama,
            'email'           => $request->email,
            'password'        => $request->password,
            'identity_number' => $request->nim,
            'role'            => 'mahasiswa',
            'status'          => 'active',
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Registrasi berhasil. Silakan login dengan NIM dan password Anda.');
    }
}
