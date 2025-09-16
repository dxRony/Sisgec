@extends('layouts.cliente')

@section('content')
<div class="container">
    <h2 class="lbl-1">Lista de Computadoras Armadas</h2>
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
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
                            foreach($computadora->componentes as $c) {
                            $cantidad = ($c->tipoComponente === 'Memoria RAM' || $c->tipoComponente === 'Almacenamiento') ? $c->pivot->cantidad: 1;
                            $precioTotal += $c->precio * $cantidad;
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
                    <form action="{{ route('cliente.carrito.agregar', $computadora->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Agregar al carrito</button>
                        <p></p>
                    </form>
                    <form action="{{ route('cliente.computadoras.personalizar', $computadora->id) }}" method="GET">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm">Personalizar</button>
                    </form>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection