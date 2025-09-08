@extends('layouts.cliente')

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
                    <h4 class="fw-bold text-primary">Compra tu computadora</h4>
                    <p>Compra una de las computadoras o personaliza la tuya con los componentes de nuestro inventario.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <h4 class="fw-bold text-primary">Consulta de inventario</h4>
                    <p>Si aun no te decides que equipo adquirir, puedes consultar nuestro inventario.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <h4 class="fw-bold text-success">Consulta el estado de tu computadora</h4>
                    <p>Una vez realizada tu compra, puedes consultar el estado de preparacion de la misma.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <h4 class="fw-bold text-danger">Realiza una queja o sugerencia</h4>
                    <p>Si tuviste algun inconveniente o tienes alguna sugerencia, haznos saberla.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection