<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Cliente\DashboardController as ClienteDashboard;
use App\Http\Controllers\Empleado\DashboardController as EmpleadoDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ComponenteController;
use App\Http\Controllers\Admin\ComputadoraController;

Route::get('/', function () {
    return view('welcome');
});
//inicio de sesion
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
//registro de usuarios
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
//rutas protegidas por tipo de usuario
Route::middleware(['auth'])->group(function () {
    Route::get('/cliente/index', [ClienteDashboard::class, 'index'])->name('cliente.index');
    Route::get('/empleado/index', [EmpleadoDashboard::class, 'index'])->name('empleado.index');
    Route::get('/admin/index', [AdminDashboard::class, 'index'])->name('admin.index');
});
//rutas de admin
Route::prefix('admin')->middleware(['auth'])->group(function () {
    //usuarios
    Route::get('/usuarios/listar', [UserController::class, 'index'])->name('admin.usuarios.listar');
    Route::get('/usuarios/registrar', [UserController::class, 'registrarV'])->name('admin.usuarios.register');
    Route::post('/usuarios/registrar', [UserController::class, 'registrar'])->name('admin.usuarios.register.post');
    Route::get('/usuarios/{id}/editar', [UserController::class, 'editarV'])->name('admin.usuarios.edit');
    Route::put('/usuarios/{id}', [UserController::class, 'editar'])->name('admin.usuarios.edit.put');
    //componentes
    Route::get('/componentes/listar', [ComponenteController::class, 'listar'])->name('admin.componentes.listar');
    Route::get('/componentes/registrar', [ComponenteController::class, 'registrarV'])->name('admin.componentes.registrar');
    Route::post('/componentes/registrar', [ComponenteController::class, 'registrar'])->name('admin.componentes.registrar.post');
    Route::get('/componentes/{id}/editar', [ComponenteController::class, 'editarV'])->name('admin.componentes.edit');
    Route::post('/componentes/{id}/editar', [ComponenteController::class, 'editar'])->name('admin.componentes.update');
    Route::delete('/componentes/{id}', [ComponenteController::class, 'eliminar'])->name('admin.componentes.destroy');
    //computadoras
    Route::get('/computadoras/listar', [ComputadoraController::class, 'listar'])->name('admin.computadoras.listar');
    Route::get('/computadoras/registrar', [ComputadoraController::class, 'registrarV'])->name('admin.computadoras.register');
    Route::post('/computadoras/registrar', [ComputadoraController::class, 'registrar'])->name('admin.computadoras.register.post');
    
});
