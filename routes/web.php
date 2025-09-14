<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Cliente\DashboardController as ClienteDashboard;
use App\Http\Controllers\Empleado\DashboardController as EmpleadoDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ComponenteController as AdminComponenteController;
use App\Http\Controllers\Admin\ComputadoraController as AdminComputerController;
use App\Http\Controllers\Admin\EnsamblajeController as AdminEnsamblajeController;
use App\Http\Controllers\Admin\ReporteController as ReporteController;

use App\Http\Controllers\Empleado\ClienteController as EmpleadoUserController;
use App\Http\Controllers\Empleado\ComputadoraController as EmpleadoComputerController;
use App\Http\Controllers\Empleado\ComponenteController as EmpleadoComponenteController;
use App\Http\Controllers\Empleado\CarritoController as EmpleadoCarritoController;
use App\Http\Controllers\Empleado\VenderController as VenderController;
use App\Http\Controllers\Empleado\EnsamblajeController as EmpleadoEnsamblajeController;

use App\Http\Controllers\Cliente\ComponenteController as ClienteComponenteController;
use App\Http\Controllers\Cliente\ComputadoraController as ClienteComputerController;
use App\Http\Controllers\Cliente\CarritoController as ClienteCarritoController;
use App\Http\Controllers\Cliente\ComprarController as ClienteComprarController;
use App\Http\Controllers\Cliente\FacturaController as ClienteFacturaController;

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
    Route::get('/usuarios/listar', [AdminUserController::class, 'index'])->name('admin.usuarios.listar');
    Route::get('/usuarios/registrar', [AdminUserController::class, 'registrarV'])->name('admin.usuarios.register');
    Route::post('/usuarios/registrar', [AdminUserController::class, 'registrar'])->name('admin.usuarios.register.post');
    Route::get('/usuarios/{id}/editar', [AdminUserController::class, 'editarV'])->name('admin.usuarios.edit');
    Route::put('/usuarios/{id}', [AdminUserController::class, 'editar'])->name('admin.usuarios.edit.put');
    //componentes
    Route::get('/componentes/listar', [AdminComponenteController::class, 'listar'])->name('admin.componentes.listar');
    Route::get('/componentes/registrar', [AdminComponenteController::class, 'registrarV'])->name('admin.componentes.registrar');
    Route::post('/componentes/registrar', [AdminComponenteController::class, 'registrar'])->name('admin.componentes.registrar.post');
    Route::get('/componentes/{id}/editar', [AdminComponenteController::class, 'editarV'])->name('admin.componentes.edit');
    Route::post('/componentes/{id}/editar', [AdminComponenteController::class, 'editar'])->name('admin.componentes.update');
    Route::delete('/componentes/{id}', [AdminComponenteController::class, 'eliminar'])->name('admin.componentes.destroy');
    //computadoras
    Route::get('/computadoras/listar', [AdminComputerController::class, 'listar'])->name('admin.computadoras.listar');
    Route::get('/computadoras/registrar', [AdminComputerController::class, 'registrarV'])->name('admin.computadoras.register');
    Route::post('/computadoras/registrar', [AdminComputerController::class, 'registrar'])->name('admin.computadoras.register.post');
    Route::get('/computadoras/{id}/editar', [AdminComputerController::class, 'editarV'])->name('admin.computadoras.edit');
    Route::put('/computadoras/{id}', [AdminComputerController::class, 'editar'])->name('admin.computadoras.update');
    Route::delete('/computadoras/{id}', [AdminComputerController::class, 'eliminar'])->name('admin.computadoras.destroy');
    //ventas
    Route::get('/ventas/listar', [App\Http\Controllers\Admin\VentaController::class, 'listar'])->name('admin.ventas.listar');
    //facturas
    Route::get('/factura/{id}', [App\Http\Controllers\Admin\FacturaController::class, 'ver'])->name('admin.factura.ver');
    //ensamblajes
    Route::get('/ensamblajes/listar', [AdminEnsamblajeController::class, 'listar'])->name('admin.ensamblajes.listar');
    Route::get('/reportes/componentesMasVendidos', [ReporteController::class, 'componentesMasVendidos'])->name('admin.reportes.componentes');
});
//rutas de empleado
Route::prefix('empleado')->middleware(['auth'])->group(function () {
    //clientes
    Route::get('/clientes/listar', [EmpleadoUserController::class, 'listar'])->name('empleado.clientes.listar');
    Route::get('/clientes/registrar', [EmpleadoUserController::class, 'registrarV'])->name('empleado.clientes.register');
    Route::post('/clientes/registrar', [EmpleadoUserController::class, 'registrar'])->name('empleado.clientes.register.post');
    Route::get('/clientes/{id}/editar', [EmpleadoUserController::class, 'editarV'])->name('empleado.clientes.edit');
    Route::put('/clientes/{id}', [EmpleadoUserController::class, 'editar'])->name('empleado.clientes.edit.put');
    //componentes
    Route::get('/componentes/listar', [EmpleadoComponenteController::class, 'listar'])->name('empleado.componentes.listar');
    //computadoras
    Route::get('/computadoras/listar', [EmpleadoComputerController::class, 'listar'])->name('empleado.computadoras.listar');
    Route::get('/computadoras/personalizar/{id}', [EmpleadoComputerController::class, 'personalizarV'])->name('empleado.computadoras.personalizar');
    Route::post('/computadoras/personalizar/{id}', [EmpleadoComputerController::class, 'personalizar'])->name('empleado.computadoras.personalizar.post');
    Route::get('/computadoras/registrar', [EmpleadoComputerController::class, 'registrarV'])->name('empleado.computadoras.register');
    Route::post('/computadoras/registrar', [EmpleadoComputerController::class, 'registrar'])->name('empleado.computadoras.register.post');
    //carrito
    Route::get('/carrito', [EmpleadoCarritoController::class, 'index'])->name('empleado.carrito.index');
    Route::post('/carrito/agregar/{id}', [EmpleadoCarritoController::class, 'agregar'])->name('empleado.carrito.agregar');
    Route::post('/carrito/eliminar/{id}', [EmpleadoCarritoController::class, 'eliminar'])->name('empleado.carrito.eliminar');
    Route::post('/carrito/agregarComponente/{id}', [EmpleadoCarritoController::class, 'agregarComponente'])->name('empleado.carrito.agregarComponente');
    Route::post('/carrito/comprar', [VenderController::class, 'realizarCompra'])->name('empleado.carrito.comprar');
    //ensamblajes
    Route::get('/ensamblajes/listar', [EmpleadoEnsamblajeController::class, 'listar'])->name('empleado.ensamblajes.listar');
    Route::get('/ensamblajes/listarMios', [EmpleadoEnsamblajeController::class, 'listarMios'])->name('empleado.ensamblajes.listarMios');
    Route::get('/ensamblajes/{id}/adquirir', [EmpleadoEnsamblajeController::class, 'adquirir'])->name('empleado.ensamblajes.adquirir');
    Route::get('/ensamblajes/{id}/ensamblar', [EmpleadoEnsamblajeController::class, 'ensamblar'])->name('empleado.ensamblajes.ensamblar');
});
//rutas de cliente
Route::prefix('cliente')->middleware(['auth'])->group(function () {
    //componentes
    Route::get('/componentes/listar', [ClienteComponenteController::class, 'listar'])->name('cliente.componentes.listar');
    //computadoras
    Route::get('/computadoras/listar', [ClienteComputerController::class, 'listar'])->name('cliente.computadoras.listar');
    Route::get('/computadoras/personalizar/{id}', [ClienteComputerController::class, 'personalizarV'])->name('cliente.computadoras.personalizar');
    Route::post('/computadoras/personalizar/{id}', [ClienteComputerController::class, 'personalizar'])->name('cliente.computadoras.personalizar.post');
    Route::get('/computadoras/registrar', [ClienteComputerController::class, 'registrarV'])->name('cliente.computadoras.register');
    Route::post('/computadoras/registrar', [ClienteComputerController::class, 'registrar'])->name('cliente.computadoras.register.post');
    //carrito
    Route::get('/carrito', [ClienteCarritoController::class, 'index'])->name('cliente.carrito.index');
    Route::post('/carrito/agregar/{id}', [ClienteCarritoController::class, 'agregar'])->name('cliente.carrito.agregar');
    Route::post('/carrito/eliminar/{id}', [ClienteCarritoController::class, 'eliminar'])->name('cliente.carrito.eliminar');
    Route::post('/carrito/agregarComponente/{id}', [ClienteCarritoController::class, 'agregarComponente'])->name('cliente.carrito.agregarComponente');
    Route::post('/carrito/comprar', [ClienteComprarController::class, 'realizarCompra'])->name('cliente.carrito.comprar');
    //compras
    Route::get('/compras/listar', [ClienteComprarController::class, 'listarCompras'])->name('cliente.compras.listar');
    //facturas
    Route::get('/factura/{id}', [ClienteFacturaController::class, 'ver'])->name('cliente.factura.ver');
});
