<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Laravel espera 'email' y 'password', pero tu tabla tiene 'password_hash'
        // Necesitamos autenticar manualmente
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && password_verify($credentials['password'], $user->password_hash)) {
            if ($user->estado === 'inactivo') {
                return back()->withErrors(['email' => 'Tu cuenta está desactivada.']);
            }

            Auth::login($user);
            $request->session()->regenerate();

            if ($user->isStaff()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('catalogo');
        }

        return back()->withErrors([
            'email' => 'Correo o contraseña incorrectos.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
