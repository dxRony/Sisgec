<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sisgec</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-5">
    <h1>Bienvenido, {{ Auth::user()->nombre }}</h1>
    <p>Tu usuario es: <strong>{{ Auth::user()->username }}</strong></p>
    <p>Rol: 
        @if(Auth::user()->rol == 1)
            Administrador
        @elseif(Auth::user()->rol == 2)
            Empleado
        @else
            Cliente
        @endif
    </p>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="btn btn-danger">Cerrar sesión</button>
    </form>
</body>
</html>
