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
            'nim' => [
                'required',
                'regex:/^[0-9]{1,12}$/',
                'unique:users,identity_number',
            ],

        ], [

            'nama.required' => 'Full name is required.',
            'username.required' => 'Username is required.',
            'username.unique' => 'Username is already in use.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'Email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'nim.required' => 'Student ID (NIM) is required.',
            'nim.regex' => 'Student ID must contain only numbers and a maximum of 12 digits.',
            'nim.unique' => 'Student ID has already been registered.',
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

        return redirect()
            ->route('login')
            ->with('success', 'Registration successful. Please login to your account.');
    }
}
