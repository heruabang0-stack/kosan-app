<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. Tampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Arahkan berdasarkan role pengguna
            if (Auth::user()->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Selamat datang Admin, ' . Auth::user()->name . '!');
            }

            return redirect()->intended(route('kos.index'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Email atau kata sandi yang Anda masukkan salah.']);
    }

    // 3. Tampilkan form register (khusus pengguna / user)
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // 4. Proses registrasi akun user baru
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'telepon'  => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email ini sudah terdaftar.',
            'telepon.required'   => 'Nomor telepon/WhatsApp wajib diisi.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'telepon'  => $validated['telepon'],
            'password' => Hash::make($validated['password']),
            'role'     => 'user', // Selalu user biasa saat mendaftar publik
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('kos.index')
            ->with('success', 'Pendaftaran berhasil! Selamat datang di CariKos, ' . $user->name . '.');
    }

    // 5. Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar.');
    }
}
