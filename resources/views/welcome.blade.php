<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sisgec</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <style>
        
    </style>
</head>
<body>
    <section class="main">
        <div class="container">
            <h1 class="display-3 fw-bold">¡Bienvenido a <span class="text-warning">Sisgec</span>!</h1>
            <p class="lead mt-3">
               Esta es una aplicacion web en la que podras realizar la compra de tu computadora, podras elegir la opcion de comprar una computadora
                ya armada o personalizar la tuya a partir de componentes disponibles en el inventario.
            </p>
            <a href="{{ route('login') }}" class="btn btn-start mt-4">Iniciar sesion</a>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <small>© {{ date('Y') }} Sisgec. Proyecto 1 - TS1</small>
    </footer>
</body>

</html>