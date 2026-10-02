<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email:rfc,dns', 'unique:users,email'],
            'password' => ['required', 'min:8', 'max:10', 'confirmed', Password::min(8)->max(10)->uncompromised()],
        ],
        [
            'name.required' => 'Nama Lengkap harus diisi',
            'name.min' => 'Nama minimal 3 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email Harus disi dengan data yang belum terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.max' => 'Password maksimal 10 karakter',
            'password.confirmed' => 'Konfirmasi Password tidak sesuai dengan password yang diberikkan',

        ]);

        //simpan data ke database
        $createAccount = User::create([
            //nama field -> isi data
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            //hash:: mengubah pw plain text menajadi karakter acak agar tidak mudah dibaca
            'password' => Hash::make($validatedData['password'])
        ]);
        //menentukan jika berhasil di simpan akan di arahkan halaman mana : return redirect()->route('home');
        //mengirimkan session untuk notifikasi berhasil : with('nama','pesan')
        return redirect()->route('login')->with('success', 'berhasil membuat akun, silahkan login');
    }
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ],
        [
            'email.required' => 'Email harus diisi',
            'password.required' => 'Password harus diisi',
        ]);
        // //untuk proses auth ambil data selain token (email dan password saja)
        // $auth = $request->except('_token');
        // // 1. cek pasangan email-pw bener atau salah
        // // 2. jika bener, simpan data di sesion/cookie web
        // // 3. kalau salah tentunkan aksi yang akan dilakukan
        // $checkAuth = Auth::attempt($auth);
        // if ($checkAuth) {
        //     $request->session()->regenerate();
        //     return redirect()->route('home')->with('success', 'Berhasil login');
        // } else {
        //     //withInput() -> mengirimkan old(data,inputan sblmnya) ke halaman logim
        //     return redirect()->route('login')->with('error', 'Email atau Password salah. Coba lagi!')->withInput();
        // }

        //yang lama
        // if (Auth::attempt($validatedData)) {
        //     $request->session()->regenerate();
        //     if (Auth::user()->role == 'admin') {
        //         return redirect()->route('admin.dashboard')->with('success', 'Berhasil login');
        //     } else {
        //         return redirect()->route('home')->with('success', 'Berhasil login');
        //     }
        // }

        //yang terbaru
        if (Auth::attempt($validatedData)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Berhasil login');
            }

            return redirect()->route('home')->with('success', 'Berhasil login');

        } else {
            return redirect()->route('login')->with('error', 'Email atau Password salah. Coba lagi!')->withInput();
        }

    }
    public function logout(request $request)
    {
        Auth::logout();
        //memastikan semua sesiaon yang ada dibuat invalid/expired
        $request->session()->invalidate();
          //bikin ulang token sesion baru
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Berhasil logout');
    }
}
