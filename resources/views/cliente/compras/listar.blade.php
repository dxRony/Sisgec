@extends('layouts.cliente')

@section('content')
<div class="container">
    <h2 class="lbl-1">Mi historial de compras</h2>

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID Venta</th>
                <th>Detalles</th>
                <th>Total</th>
                <th>Empleado encargado</th>
                <th>Estado</th>
                <th>Factura</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($compras as $compra)
            <tr>
                <td>{{ $compra->id }}</td>
                <td>
                    <ul>
                        @foreach($compra->detalles as $detalle)
                        @if($detalle->computadora)
                        <li>
                            <strong>Computadora:</strong> {{ $detalle->computadora->id}}
                            (x{{ $detalle->cantidad }})
                            <ul>
                                @foreach($detalle->computadora->componentes as $componente)
                                <li>{{ $componente->tipoComponente }}/{{ $componente->marca }} - Q{{ number_format($componente->precio, 2) }}</li>
                                @endforeach
                            </ul>
                        </li>
                        @elseif($detalle->componente)
                        <li>
                            <strong>Componente:</strong> {{ $detalle->componente->tipoComponente }} / {{ $detalle->componente->marca }} - Q{{ number_format($detalle->componente->precio, 2) }}
                            (x{{ $detalle->cantidad }})
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </td>
                <td>Q{{ number_format($compra->total, 2) }}</td>
                @php
                $nombreEmpleado = $factura->venta->empleado->name ?? null;
                @endphp
                <p></p>
                <td>{{ !$nombreEmpleado || $nombreEmpleado === 'Administrador General' ? 'Sistema' : $nombreEmpleado }}</td>
                <td>
                    @if($compra->estado === 'en proceso')
                    <span class="badge bg-warning text-dark">En proceso</span>
                    @elseif($compra->estado === 'finalizada')
                    <span class="badge bg-success">Finalizada</span>
                    @else
                    <span class="badge bg-secondary">{{ ucfirst($compra->estado) }}</span>
                    @endif
                </td>
                <td>
                    @if($compra->estado === 'finalizada' && $compra->factura)
                    <a href="{{ route('cliente.factura.ver', $compra->factura->id) }}" class="btn btn-primary btn-sm">
                        Ver Factura
                    </a>
                    @else
                    <span class="text-muted">No disponible</span>
                    @endif
                </td>
                <td>{{ $compra->fecha }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection