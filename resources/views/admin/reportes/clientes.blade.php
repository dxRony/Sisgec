@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="lbl-1">Reportes de Clientes</h2>

    <!-- Filtros -->
    <form method="GET" action="{{ route('admin.reportes.clientes') }}" class="row g-3 mb-4">
        <div class="col-md-2">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select">
                <option value="">Todos</option>
                <option value="queja" {{ request('tipo')=='queja' ? 'selected' : '' }}>Queja</option>
                <option value="sugerencia" {{ request('tipo')=='sugerencia' ? 'selected' : '' }}>Sugerencia</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Empleado</label>
            <select name="idEmpleado" class="form-select">
                <option value="">Todos</option>
                @foreach($empleados as $empleado)
                <option value="{{ $empleado->id }}" {{ request('idEmpleado')==$empleado->id ? 'selected' : '' }}>
                    {{ $empleado->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Componente</label>
            <select name="idComponente" class="form-select">
                <option value="">Todos</option>
                @foreach($componentes as $componente)
                <option value="{{ $componente->id }}" {{ request('idComponente')==$componente->id ? 'selected' : '' }}>
                    {{ $componente->tipoComponente }} - {{ $componente->marca }} - Q.{{ $componente->precio }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Computadora</label>
            <select name="idComputadora" class="form-select">
                <option value="">Todas</option>
                @foreach($computadoras as $computadora)
                <option value="{{ $computadora->id }}" {{ request('idComputadora')==$computadora->id ? 'selected' : '' }}>
                   ID: {{ $computadora->id }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Cliente (NIT)</label>
            <select name="nitUsuario" class="form-select">
                <option value="">Todos</option>
                @foreach($clientes as $cliente)
                <option value="{{ $cliente->id }}" {{ request('nitUsuario')==$cliente->id ? 'selected' : '' }}>
                    {{ $cliente->name }} ({{ $cliente->id }})
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" value="{{ request('fecha') }}">
        </div>

        <div class="col-12 mt-3">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('admin.reportes.clientes') }}" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>

    <!-- Tabla -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Empleado</th>
                <th>Componente</th>
                <th>Computadora</th>
                <th>Cliente</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reportes as $reporte)
            <tr>
                <td>{{ $reporte->id }}</td>
                <td>{{ ucfirst($reporte->tipo) }}</td>
                <td>{{ $reporte->descripcion }}</td>
                <td>{{ $reporte->empleado->name ?? 'N/A' }}</td>
                <td>
                    @if($reporte->componente)
                    {{ $reporte->componente->tipoComponente }} / {{ $reporte->componente->marca }} - Q{{ number_format($reporte->componente->precio, 2) }}
                    @else
                    N/A
                    @endif
                </td>
                <td>{{ $reporte->computadora->id ?? 'N/A' }}</td>
                <td>{{ $reporte->usuario->name ?? $reporte->nitUsuario }}</td>
                <td>{{ $reporte->fecha }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">No hay reportes con esos filtros.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection