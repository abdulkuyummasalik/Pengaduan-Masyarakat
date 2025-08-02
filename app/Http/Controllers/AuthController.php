<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.index');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        // Validasi input dari pengguna
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required|min:8',
        ]);

        // Coba login menggunakan Auth::attempt
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate(); // Regenerasi session untuk keamanan
            $user = Auth::user();

            // Redirect berdasarkan role user
            switch ($user->role) {
                case 'GUEST':
                    return redirect()->route('report.article.index');
                case 'STAFF':
                    return redirect()->route('response.index');
                default:
                    return redirect()->route('dashboard');
            }
        }

        // Jika email belum terdaftar, buat user baru secara otomatis
        if (!User::where('email', $request->email)->exists()) {
            $emailToName = substr($request->email, 0, strpos($request->email, '@')) ?: $request->email;

            $user = User::create([
                'name' => ucfirst($emailToName), // Ubah menjadi lebih ramah pengguna
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'GUEST',
            ]);

            // Login user baru dan redirect
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->route('report.article.index');
        }

        // Jika login gagal dan user sudah ada
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
