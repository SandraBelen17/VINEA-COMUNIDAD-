@extends('layouts.acceso')

@section('titulo', 'Iniciar sesión')

@section('contenido')
    <div class="titulo">
        <h1>Iniciar sesión</h1>
        <p>Entra para pedir citas o administrar la parroquia.</p>
    </div>

    @include('partials.avisos')

    @if ($errors->any())
        <p class="aviso aviso-error" role="alert">{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="{{ route('login') }}" class="formulario" novalidate>
        @csrf

        <div class="campo">
            <label for="email">Correo electrónico</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                   placeholder="tucorreo@ejemplo.com" autocomplete="email" required autofocus>
        </div>

        <div class="campo">
            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password"
                   placeholder="Tu contraseña" autocomplete="current-password" required>
        </div>

        <label class="campo-check">
            <input type="checkbox" name="recordarme" value="1" @checked(old('recordarme'))>
            Mantener mi sesión iniciada
        </label>

        <button type="submit" class="boton boton-ancho">Entrar</button>
    </form>

    <p class="acceso-cambio">¿No tienes cuenta? <a href="{{ route('registro') }}">Regístrate</a></p>
@endsection
