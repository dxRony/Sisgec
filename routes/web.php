<?php

use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SumaController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Cliente\DashboardController as ClienteDashboard;
use App\Http\Controllers\Empleado\DashboardController as EmpleadoDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/suma', [SumaController::class, 'index']);

Route::post('/suma', [SumaController::class, 'suma']);

Route::get('/productos', [ProductoController::class, 'index']);

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/cliente/index', [ClienteDashboard::class, 'index'])->name('cliente.index');
    Route::get('/empleado/index', [EmpleadoDashboard::class, 'index'])->name('empleado.index');
    Route::get('/admin/index', [AdminDashboard::class, 'index'])->name('admin.index');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/usuarios/listar', [UserController::class, 'index'])->name('admin.usuarios.listar');
    Route::get('/usuarios/registrar', [UserController::class, 'registrarV'])->name('admin.usuarios.register');
    Route::post('/usuarios/registrar', [UserController::class, 'registrar'])->name('admin.usuarios.register.post');
});

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');



