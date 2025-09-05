<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{
     // Mostrar formulario de registro
    public function showRegistrationForm()
    {
        return view('login.register');
    }

    // Procesar registro
    public function register(Request $request)
    {
        $request->validate([
            'nit' => 'required|integer|unique:Usuario,nit',
            'nombre' => 'required|string|max:100',
            'username' => 'required|string|max:100|unique:Usuario,username',
            'password' => 'required|string|min:6|confirmed',
            'celular' => 'required|numeric',
        ]);

        Usuario::create([
            'nit' => $request->nit,
            'nombre' => $request->nombre,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'celular' => $request->celular,
            'activo' => true,
            'rol' => 3, // ✅ siempre rol cliente
        ]);

        return redirect()->route('login')->with('success', 'Cuenta creada correctamente. Ahora puedes iniciar sesión.');
    }
}
