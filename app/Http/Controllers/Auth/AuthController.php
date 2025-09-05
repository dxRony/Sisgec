<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\alert;

class AuthController extends Controller
{
   public function showLogin()
    {
        return view('login.login');
    }

    public function login(Request $request)
    {
         echo "<script>alert('Todos los campos son obligatorios');</script>";
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $usuario = Usuario::where('username', $request->username)->first();

        if ($usuario && Hash::check($request->password, $usuario->password)) {            
            Auth::login($usuario);
             echo "<script>alert('Todos los campos son obligatorios');</script>";
            return redirect()->route('dashboard');
        }
         echo "<script>alert('Todos los campos son obligatorios');</script>";
        return back()->withErrors([
            'login' => 'Usuario o contraseña incorrectos',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
