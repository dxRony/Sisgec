@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="lbl-1">Computadoras más vendidas</h2>

    @if($computadoras->isEmpty())
        <div class="alert alert-info">No hay registros de ventas de computadoras.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>ID Computadora</th>
                    <th>Componentes</th>
                    <th>Total Vendidas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($computadoras as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->computadora->id ?? 'N/A' }}</td>
                    <td>
                        <ul>
                            @foreach($item->computadora->componentes as $comp)
                                <li>{{ $comp->tipoComponente }} / {{ $comp->marca }} - Q{{ number_format($comp->precio, 2) }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $item->totalVendido }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
