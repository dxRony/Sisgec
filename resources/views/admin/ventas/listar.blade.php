@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="lbl-1">Historial de Compras (Administrador)</h2>

    {{-- 🔹 Formulario de filtros --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Fecha Inicio</label>
            <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Fecha Fin</label>
            <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Cliente</label>
            <input type="text" name="cliente" value="{{ request('cliente') }}" class="form-control" placeholder="Nombre o ID">
        </div>
        <div class="col-md-3">
            <label class="form-label">Tipo de componente</label>
            <input type="text" name="producto" value="{{ request('producto') }}" class="form-control" placeholder="Procesador, Memoria RAM, etc.">
        </div>
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-dark">Filtrar</button>
            <a href="{{ route('admin.ventas.listar') }}" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>

    {{-- 🔹 Tabla de compras --}}
    <table class="table table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID Venta</th>
                <th>Cliente</th>
                <th>Detalles</th>
                <th>Total</th>
                <th>Empleado encargado</th>
                <th>Estado</th>
                <th>Factura</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($compras as $compra)
            <tr>
                <td>{{ $compra->id }}</td>
                <td>{{ $compra->usuario->name ?? 'Desconocido' }} (ID: {{ $compra->nitUsuario }})</td>
                <td>
                    <ul>
                        @foreach($compra->detalles as $detalle)
                            @if($detalle->computadora)
                                <li>
                                    <strong>Computadora #{{ $detalle->computadora->id }}</strong> (x{{ $detalle->cantidad }})
                                    <ul>
                                        @foreach($detalle->computadora->componentes as $componente)
                                            <li>{{ $componente->tipoComponente }} / {{ $componente->marca }} - Q{{ number_format($componente->precio, 2) }}</li>
                                        @endforeach
                                    </ul>
                                </li>
                            @elseif($detalle->componente)
                                <li>
                                    <strong>Componente:</strong> {{ $detalle->componente->tipoComponente }} / {{ $detalle->componente->marca }}
                                    - Q{{ number_format($detalle->componente->precio, 2) }}
                                    (x{{ $detalle->cantidad }})
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </td>
                <td>Q{{ number_format($compra->total, 2) }}</td>
                <td>{{ $compra->empleado->name ?? 'Sistema' }}</td>
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
                        <a href="{{ route('admin.factura.ver', $compra->factura->id) }}" class="btn btn-primary btn-sm">
                            Ver Factura
                        </a>
                    @else
                        <span class="text-muted">No disponible</span>
                    @endif
                </td>
                <td>{{ $compra->fecha }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted">No se encontraron compras</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
