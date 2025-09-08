<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sisgec - admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand">Sisgec</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="/admin/index">Inicio</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Gestión de usuarios</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/admin/usuarios/listar">Listar usuarios</a></li>
                            <li><a class="dropdown-item" href="/admin/usuarios/registrar">Registrar usuario</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Gestión de inventario</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/admin/productos">Listar computadoras</a></li>
                            <li><a class="dropdown-item" href="/admin/componentes">Listar componentes</a></li>
                            <li><a class="dropdown-item" href="/admin/componentes">Registrar componentes</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Visualización de reportes</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/admin/reportes/ventas">pendiente</a></li>
                            <li><a class="dropdown-item" href="/admin/reportes/inventario">pendiente</a></li>
                            <li><a class="dropdown-item" href="/admin/reportes/usuarios">pendiente</a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link text-danger">
                                Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>