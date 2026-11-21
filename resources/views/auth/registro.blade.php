@extends('layouts.acceso')

@section('titulo', 'Crear cuenta')

@section('contenido')
    <div class="titulo">
        <h1>Crear cuenta</h1>
        <p>Con tu cuenta puedes pedir citas y ver su estado.</p>
    </div>

    @include('partials.avisos')

    <form method="POST" action="{{ route('registro') }}" class="formulario" novalidate>
        @csrf

        <div class="campo">
            <label for="name">Nombre completo</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}"
                   placeholder="Tu nombre" autocomplete="name" required autofocus>
            @error('name') <span class="error-campo">{{ $message }}</span> @enderror
        </div>

        <div class="campo">
            <label for="email">Correo electrónico</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                   placeholder="tucorreo@ejemplo.com" autocomplete="email" required>
            @error('email') <span class="error-campo">{{ $message }}</span> @enderror
        </div>

        <div class="campo">
            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password"
                   placeholder="Mínimo 8 caracteres" autocomplete="new-password" required>
            @error('password') <span class="error-campo">{{ $message }}</span> @enderror
        </div>

        <div class="campo">
            <label for="password_confirmation">Confirma tu contraseña</label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                   placeholder="Escríbela otra vez" autocomplete="new-password" required>
        </div>

        <button type="submit" class="boton boton-ancho">Crear cuenta</button>
    </form>

    <p class="acceso-cambio">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
@endsection
