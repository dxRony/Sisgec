@extends('layouts.app')

@section('content')

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Descripcion</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($productos as $producto)
        <tr>
            <td>{{ $producto->id }} </td>
            <td>{{ $producto->nombre }} </td>
            <td>{{ $producto->precio }} </td>
            <td>{{ $producto->descripcion }} </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
