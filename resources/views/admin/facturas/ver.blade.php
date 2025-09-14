@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h4>{{ $factura->numeroFactura }}</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-6">
                    <h5>Datos de la Factura</h5>
                    <p><strong>NIT Cliente:</strong> {{ $factura->nit }}</p>
                    <p><strong>Fecha:</strong> {{ $factura->fecha }}</p>
                </div>
                <div class="col-6 text-end">
                    <h5>Atendido por</h5>
                    @php
                    $nombreEmpleado = $factura->venta->empleado->name ?? null;
                    @endphp
                    <p>{{ !$nombreEmpleado || $nombreEmpleado === 'Administrador General' ? 'Sistema' : $nombreEmpleado }}</p>
                </div>
            </div>

            <h5>Detalles de la compra</h5>
            <table class="table table-bordered align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($factura->venta->detalles as $detalle)
                    @php
                    $subtotal = $detalle->subtotal;
                    $total += $subtotal;
                    @endphp
                    <tr>
                        <td>
                            @if($detalle->computadora)
                            <strong>Computadora (ID: {{ $detalle->computadora->id }})</strong>
                            <ul>
                                @foreach($detalle->computadora->componentes as $comp)
                                <li>{{ $comp->tipoComponente }} / {{ $comp->marca }} - Q{{ number_format($comp->precio, 2) }}</li>
                                @endforeach
                            </ul>
                            @elseif($detalle->componente)
                            <strong>Componente:</strong> {{ $detalle->componente->tipoComponente }} / {{ $detalle->componente->marca }}
                            @endif
                        </td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>Q{{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="table-dark text-white">
                        <td colspan="2" class="text-end"><strong>Total</strong></td>
                        <td><strong>Q{{ number_format($total, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        <a href="{{ route('admin.ventas.listar') }}" class="btn btn-secondary">Regresar</a>
    </div>
</div>
@endsection