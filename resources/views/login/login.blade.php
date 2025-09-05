<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sisgec - login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="login-container">
        <h2>Iniciar sesión</h2>

        @if($errors->any())
        <div style="color:red; text-align: center; margin-bottom: 1rem;">
            {{ $errors->first('login') }}
        </div>
        @endif
        <!-- resources/views/login/login.blade.php -->
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Nombre de usuario</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label for="pasword" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar sesion</button>
            <p></p>
            <a href="/" class="btn btn-secondary">Regresar</a>
            <a href="{{ route('register') }}">Registrarse?</a>
        </form>
    </div>

</body>

</html>