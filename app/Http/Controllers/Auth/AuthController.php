<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('Auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'min:9'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $credentials = $request->only('phone', 'password');
        $credentials['is_active'] = 1;

        if (Auth::attempt($credentials, $request->filled('remember_me'))) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'phone' => 'Telefon raqam yoki parol noto‘g‘ri',
        ])->onlyInput('phone');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
