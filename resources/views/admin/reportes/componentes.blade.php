@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="lbl-1">Componentes más vendidos</h2>

    @if($componentes->isEmpty())
    <div class="alert alert-info">No hay registros de ventas de componentes.</div>
    @else
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Tipo de Componente</th>
                <th>Marca</th>
                <th>Precio (Q)</th>
                <th>Total Vendido</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($componentes as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->componente->tipoComponente ?? 'N/A' }}</td>
                <td>{{ $item->componente->marca ?? 'N/A' }}</td>
                <td>Q{{ number_format($item->componente->precio ?? 0, 2) }}</td>
                <td>{{ $item->totalVendido }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection