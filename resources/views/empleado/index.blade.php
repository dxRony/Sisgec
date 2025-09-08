@extends('layouts.empleado')

@section('content')
<h1 class="lbl-1">Bienvenido cliente</h1>
<section class="features">
    <div class="container">
        <div class="row text-center mb-4">
            <h2 class="fw-bold">¿Que puedes hacer en Sisgec?</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <h4 class="fw-bold text-primary">Registrar a clientes</h4>
                    <p>Si hay un cliente no registrado, puedes registrarlo.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <h4 class="fw-bold text-primary">Consulta de inventario</h4>
                    <p>Si un cliente te solicita informacion, la puedes adquirir en el sistema.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <h4 class="fw-bold text-success">Venta de computadoras</h4>
                    <p>Si un cliente quiere realizar la compra de su equipo, puedes realizarla.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <h4 class="fw-bold text-danger">Gestiona trabajos de armado</h4>
                    <p>Puedes visualizar y registrar cambios conforme a vayas realizando armados de las computadoras personalizadas.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection