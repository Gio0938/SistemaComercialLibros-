<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Cambiar a User
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Cambiar el campo 'email' a 'email' y usar el guard por defecto
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            // Actualizar último acceso
            $user = Auth::user();
            $user->ultimo_acceso = now();
            $user->save();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // Método para crear usuario admin (solo testing)
    public function createUser()
    {
        User::create([
            'nombre' => 'Administrador',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'rol' => 'admin',
            'activo' => 1,
        ]);

        return "Usuario creado exitosamente";
    }
}
