@extends('layouts.admin')

@section('content')
<h1 class="lbl-1">Bienvenido administrador</h1>
<section class="features">
    <div class="container">
        <div class="row text-center mb-4">
            <h2 class="fw-bold">¿Que puedes hacer en Sisgec?</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <h4 class="fw-bold text-primary">Gestión de usuarios</h4>
                    <p>Administra clientes y empleados.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <h4 class="fw-bold text-success">Control de productos</h4>
                    <p>Registra y supervisa el inventario de manera eficiente.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <h4 class="fw-bold text-danger">Consulta de reportes</h4>
                    <p>Accede a estadisticas clave para la toma de decisiones estrategicas.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection