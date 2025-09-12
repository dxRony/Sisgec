@extends('layouts.cliente')

@section('content')
<div class="container">
    <h2 class="lbl-1">Carrito de Compras</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(empty($carrito))
    <div class="alert alert-info">
        <h4>El carrito de compras esta vacio</h4>
    </div>
    @else
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID Computadora</th>
                <th>Nombre</th>
                <th>Precio Unitario</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($carrito as $item)
            @php
            $subtotal = $item['precio'] * $item['cantidad'];
            $total += $subtotal;
            @endphp
            <tr>
                <td>{{ $item['id'] }}</td>
                <td>{{ $item['nombre'] ?? 'Computadora #' . $item['id'] }}</td>
                <td>Q{{ number_format($item['precio'], 2) }}</td>
                <td>{{ $item['cantidad'] }}</td>
                <td>Q{{ number_format($subtotal, 2) }}</td>
                <td>
                    <form action="{{ route('carrito.eliminar', $item['id']) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
            <tr class="table-secondary">
                <td colspan="4" class="text-end"><strong>Total</strong></td>
                <td><strong>Q{{ number_format($total, 2) }}</strong></td>
                <td>
                    <form action="{{ route('carrito.comprar') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            Realizar compra
                        </button>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
    @endif
</div>
@endsection