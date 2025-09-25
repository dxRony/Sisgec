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

    public function register(Request $request)
    {
        //validando datos del form
        $request->validate([
            'nit' => 'required|integer|unique:users,id',
            'nombre' => 'required|string|max:100',
            'username' => 'required|string|max:20|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'celular' => 'required|numeric',
        ]);
        //creando usuario cliente
        User::create([
            'id' => $request->nit,
            'name' => $request->nombre,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'celular' => $request->celular,
            'email' => $request->username . '@sisgec.com',
            'activo' => true,
            'rol' => 3,
        ]);
        return redirect()->route('login')->with('success', 'Registro exitoso.');
    }
}
