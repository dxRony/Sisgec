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
        $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

     $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'activo' => 1
        ];

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        $user = Auth::user();
        
        switch ($user->rol) {
            case 1: return redirect()->route('admin.index');
            case 2: return redirect()->route('empleado.index');
            case 3: return redirect()->route('cliente.index');
            default:
                Auth::logout();
                return back()->withErrors(['login' => 'Rol no válido']);
        }
    }

    return back()->withErrors(['login' => 'Credenciales incorrectas']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
