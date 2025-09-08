<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cliente</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="login-container" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-3">Registro de Cliente</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="mb-3">
                <label for="nit" class="form-label">NIT</label>
                <input type="text" name="nit" class="form-control" required value="{{ old('nit') }}">
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo</label>
                <input type="text" name="nombre" class="form-control" required value="{{ old('nombre') }}">
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Usuario</label>
                <input type="text" name="username" class="form-control" required value="{{ old('username') }}">
            </div>

            <div class="mb-3">
                <label for="celular" class="form-label">Celular</label>
                <input type="number" min="100" name="celular" class="form-control" required value="{{ old('celular') }}">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Registrarse</button>
            <p></p>
            <a href="/login" class="btn btn-secondary w-100">Regresar</a>
        </form>
    </div>
</body>
</html>
