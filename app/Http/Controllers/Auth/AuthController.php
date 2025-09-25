<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('login.login');
    }

    public function login(Request $request)
    {
        //validando datos del form
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        // credenciaales para el intento de autenticacion
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        //iniciando sesion
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            //obteniendo usuario de la sesion
            $user = Auth::user();
            //validando que usuario este activo
            if ($user->activo != 1) {
                Auth::logout();
                return back()->withErrors(['login' => 'Usuario inactivo. Contacte al administrador.']);
            }
            //determinando vista a traves del rol del usuario
            switch ($user->rol) {
                case 1:
                    return redirect()->route('admin.index');
                case 2:
                    return redirect()->route('empleado.index');
                case 3:
                    return redirect()->route('cliente.index');
            }
        }
        return back()->withErrors(['login' => 'Credenciales incorrectas']);
    }

    public function logout(Request $request)
    {
        //removiendo usuario de la sesion
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return view('/welcome');
    }
}
