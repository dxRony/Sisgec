@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="lbl-1">Lista de Computadoras</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Stock</th>
                <th>Componentes</th>
                <th>Precio</th>
                <th>Accion</th>
            </tr>
        </thead>
        <tbody>
            @foreach($computadoras as $computadora)
            <tr>
                <td><strong>{{ $computadora->id }}</strong></td>
                <td>{{ $computadora->disponibilidad }}</td>
                <td>
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Marca</th>
                                <th>Detalles</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $precioTotal = 0;
                            foreach($computadora->componentes as $componente) {
                            $cantidad = ($componente->tipoComponente === 'Memoria RAM' || $componente->tipoComponente === 'Almacenamiento') ? $componente->pivot->cantidad: 1;
                            $precioTotal += $componente->precio * $cantidad;
                            }
                            @endphp
                            @foreach($computadora->componentes as $componente)
                            <tr>
                                <td>{{ $componente->tipoComponente }}</td>
                                <td>{{ $componente->marca }}</td>
                                <td>
                                    @if($componente->tipoComponente === 'Procesador')
                                    Velocidad: {{ $componente->velocidad }} GHz <br>
                                    Núcleos: {{ $componente->nucleos }} <br>
                                    Consumo: {{ $componente->consumoEnergetico }} W
                                    @elseif($componente->tipoComponente === 'Fuente De Poder')
                                    Potencia: {{ $componente->potencia }} W <br>
                                    Eficiencia: {{ $componente->eficiencia }}
                                    @elseif($componente->tipoComponente === 'Gabinete')
                                    Color: {{ $componente->color }}
                                    @elseif($componente->tipoComponente === 'Memoria RAM')
                                    Velocidad: {{ $componente->velocidad }} MHz <br>
                                    Capacidad: {{ $componente->capacidad }} GB <br>
                                    Tipo: {{ $componente->tipo }} <br>
                                    Consumo: {{ $componente->consumoEnergetico }} W
                                    @elseif($componente->tipoComponente === 'Almacenamiento')
                                    Capacidad: {{ $componente->capacidad }} GB <br>
                                    Tipo: {{ $componente->tipo }} <br>
                                    Consumo: {{ $componente->consumoEnergetico }} W
                                    @endif
                                </td>
                                <td>
                                    @if($componente->tipoComponente === 'Memoria RAM' || $componente->tipoComponente === 'Almacenamiento')
                                    {{ $componente->pivot->cantidad }}
                                    @else
                                    1
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                <td>
                    Q{{ number_format($precioTotal, 2) }}
                </td>
                <td>
                    <a href="{{ route('admin.computadoras.edit', $computadora->id) }}" class="btn btn-warning btn-sm">Modificar sotck</a>
                    <p></p>
                    <form action="{{ route('admin.computadoras.destroy', $computadora->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Eliminar la computadora, en caso de tener datos incorrectos');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection