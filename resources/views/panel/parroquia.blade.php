@extends('layouts.panel')

@section('titulo', 'Datos de la parroquia')

@php
    $valor = fn (string $campo) => old($campo, $parroquia->$campo);
@endphp

@section('contenido')
    <div class="panel-titulo">
        <div>
            <h1>Datos de la parroquia</h1>
            <p>Aparecen en el sitio: pie de página y contacto. Solo el párroco puede editarlos.</p>
        </div>
    </div>

    <div class="parroquia-logo">
        <img src="{{ asset('img/logo-maria.png') }}" alt="Logotipo de la parroquia">
        <span class="texto-suave nota-chica">El logotipo es fijo y no se cambia desde aquí.</span>
    </div>

    <form method="POST" action="{{ route('panel.parroquia.guardar') }}" class="formulario-parroquia" novalidate>
        @csrf
        @method('PUT')

        <div class="campos-fila">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input id="nombre" name="nombre" type="text" maxlength="150" value="{{ $valor('nombre') }}" placeholder="Parroquia María Madre de Dios" required>
                @error('nombre') <span class="error-campo">{{ $message }}</span> @enderror
            </div>

            <div class="campo">
                <label for="correo">Correo</label>
                <input id="correo" name="correo" type="email" maxlength="150" value="{{ $valor('correo') }}" placeholder="contacto@vinea.com">
                @error('correo') <span class="error-campo">{{ $message }}</span> @enderror
            </div>

            <div class="campo">
                <label for="telefono">Teléfono</label>
                <input id="telefono" name="telefono" type="tel" maxlength="20" value="{{ $valor('telefono') }}" placeholder="238 123 4567">
                @error('telefono') <span class="error-campo">{{ $message }}</span> @enderror
            </div>

            <div class="campo">
                <label for="direccion">Dirección</label>
                <input id="direccion" name="direccion" type="text" maxlength="255" value="{{ $valor('direccion') }}" placeholder="Calle, número, colonia, C. P. y ciudad">
                @error('direccion') <span class="error-campo">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="campo">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="4" maxlength="5000" placeholder="Historia y vida de la comunidad">{{ $valor('descripcion') }}</textarea>
            @error('descripcion') <span class="error-campo">{{ $message }}</span> @enderror
        </div>

        <div class="parroquia-pie">
            <span class="texto-suave nota-chica">
                Última actualización: {{ $parroquia->actualizacion_texto ?? 'todavía no se ha editado' }}
            </span>
            <div class="botones">
                <a href="{{ route('panel.parroquia') }}" class="boton boton-linea">Cancelar</a>
                <button type="submit" class="boton">Guardar cambios</button>
            </div>
        </div>
    </form>
@endsection
