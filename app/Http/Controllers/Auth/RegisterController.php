<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{
    public function index()
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

        User::create([
            'nit' => $request->nit,
            'nombre' => $request->nombre,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'celular' => $request->celular,
            'activo' => true,
            'rol' => 3,
        ]);

        return redirect()->route('login')->with('success', 'Registro exitoso.');
    }
}
