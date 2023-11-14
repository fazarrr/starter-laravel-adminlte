<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    User,
};

class AuthController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = new User;
        $this->middleware('guest')->except('logout', 'logoutNonAktif');
    }

    function index()
    {
        $data = [
            'title'     => 'Login'
        ];

        return view("auth/v_login", $data);
    }

    // LOGIN
    function Authentication(Request $request)
    {
        $request->validate(
            [
                'email'     => 'required|exists:users',
                'password'  => 'required'
            ],
            [
                'email.required'        => 'Email wajib diisi',
                'email.exists'          => 'Email tidak terdaftar',
                'password.required'     => 'Password wajib diisi',
            ]
        );

        $infoLogin = [
            'email'     => $request->email,
            'password'  => $request->password
        ];

        if (Auth::attempt($infoLogin)) {
            if (auth()->user()->is_active == 0 || auth()->user()->is_active == null) {
                return redirect('/login/logout-nonaktif');
            } else {
                return redirect('/')->with(['success' => 'Berhasil login!']);
            }
        } else {
            return redirect('/login')->with(['error' => 'Password salah!']);
        }
    }

    // LOGOUT
    public function logout()
    {
        Auth::logout();

        return redirect('/login')->with(['success' => 'Berhasil logout!']);
    }

    // LOGOUT USER NON AKTIF
    public function logoutNonAktif()
    {
        Auth::logout();
        return redirect('/login')->with(['warning' => 'User Anda Non Aktif, Silahkan menghubungi Admin!']);
    }
}
