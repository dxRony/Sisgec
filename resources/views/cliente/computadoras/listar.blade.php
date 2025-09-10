@extends('layouts.cliente')

@section('content')
<div class="container">
    <h2 class="lbl-1">Lista de Computadoras Armadas</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Stock</th>
                <th>Componentes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($computadoras as $comp)
            <tr>
                <td><strong>{{ $comp->id }}</strong></td>
                <td>{{ $comp->disponibilidad }}</td>
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
                            @foreach($comp->componentes as $c)
                            <tr>
                                <td>{{ $c->tipoComponente }}</td>
                                <td>{{ $c->marca }}</td>
                                <td>
                                    @if($c->tipoComponente === 'Procesador')
                                    Velocidad: {{ $c->velocidad }} GHz <br>
                                    Núcleos: {{ $c->nucleos }} <br>
                                    Consumo: {{ $c->consumoEnergetico }} W
                                    @elseif($c->tipoComponente === 'Fuente De Poder')
                                    Potencia: {{ $c->potencia }} W <br>
                                    Eficiencia: {{ $c->eficiencia }}
                                    @elseif($c->tipoComponente === 'Gabinete')
                                    Color: {{ $c->color }}
                                    @elseif($c->tipoComponente === 'Memoria RAM')
                                    Velocidad: {{ $c->velocidad }} MHz <br>
                                    Capacidad: {{ $c->capacidad }} GB <br>
                                    Tipo: {{ $c->tipo }} <br>
                                    Consumo: {{ $c->consumoEnergetico }} W
                                    @elseif($c->tipoComponente === 'Almacenamiento')
                                    Capacidad: {{ $c->capacidad }} GB <br>
                                    Tipo: {{ $c->tipo }} <br>
                                    Consumo: {{ $c->consumoEnergetico }} W
                                    @endif
                                </td>
                                <td>
                                    @if($c->tipoComponente === 'Memoria RAM' || $c->tipoComponente === 'Almacenamiento')
                                    {{ $c->pivot->cantidad }}
                                    @else
                                    1
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>                
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection