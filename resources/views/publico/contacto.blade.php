@extends('layouts.publico')

@section('titulo', 'Contacto')

@php
    // Si el visitante tiene sesión, su nombre y correo ya vienen escritos
    $usuario = auth()->user();
@endphp

@section('contenido')
    <section class="portada">
        <img src="{{ asset('img/fachada.jpg') }}" alt="" style="object-position: center 50%">
        <div class="slider-velo" aria-hidden="true"></div>
        <div class="portada-texto">
            <span class="etiqueta">Contacto</span>
            <h1>¿En qué podemos ayudarte?</h1>
            <p>Escríbenos y la oficina parroquial te responderá.</p>
        </div>
    </section>

    <section class="seccion-contacto">
        <div class="contenedor contacto-columnas">
            <div class="contacto-formulario" id="escribenos">
                <h2>Escríbenos</h2>

                @include('partials.avisos')

                <form method="POST" action="{{ route('contacto.enviar') }}" class="formulario" novalidate>
                    @csrf

                    <div class="campos-fila">
                        <div class="campo">
                            <label for="nombre">Nombre</label>
                            <input id="nombre" name="nombre" type="text" maxlength="150" autocomplete="name"
                                   value="{{ old('nombre', $usuario?->name) }}" placeholder="Tu nombre completo" required>
                            @error('nombre') <span class="error-campo">{{ $message }}</span> @enderror
                        </div>

                        <div class="campo">
                            <label for="correo">Correo</label>
                            <input id="correo" name="correo" type="email" maxlength="150" autocomplete="email"
                                   value="{{ old('correo', $usuario?->email) }}" placeholder="tucorreo@ejemplo.com" required>
                            @error('correo') <span class="error-campo">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="campos-fila">
                        <div class="campo">
                            <label for="telefono">Teléfono <span class="texto-suave">(opcional)</span></label>
                            <input id="telefono" name="telefono" type="tel" maxlength="20" autocomplete="tel"
                                   value="{{ old('telefono') }}" placeholder="238 000 0000">
                            @error('telefono') <span class="error-campo">{{ $message }}</span> @enderror
                        </div>

                        <div class="campo">
                            <label for="asunto">Asunto</label>
                            <input id="asunto" name="asunto" type="text" maxlength="150"
                                   value="{{ old('asunto') }}" placeholder="Motivo de tu mensaje" required>
                            @error('asunto') <span class="error-campo">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="campo">
                        <label for="mensaje">Mensaje</label>
                        <textarea id="mensaje" name="mensaje" rows="4" maxlength="2000" placeholder="Escribe tu mensaje" required>{{ old('mensaje') }}</textarea>
                        @error('mensaje') <span class="error-campo">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <button type="submit" class="boton">Enviar mensaje</button>
                    </div>
                </form>
            </div>

            <div class="contacto-datos">
                <h2>Visítanos</h2>

                <ul class="lista-datos">
                    <li>
                        <span class="dato-etiqueta">Dirección</span>
                        <span class="dato-valor">{{ $direccion ?: '[pendiente]' }}</span>
                    </li>
                    <li>
                        <span class="dato-etiqueta">Teléfono</span>
                        @if ($telefono)
                            <a href="tel:{{ preg_replace('/\D/', '', $telefono) }}" class="dato-valor">{{ $telefonoTexto }}</a>
                        @else
                            <span class="dato-valor">[pendiente]</span>
                        @endif
                    </li>
                    <li>
                        <span class="dato-etiqueta">Correo</span>
                        @if ($correo)
                            <a href="mailto:{{ $correo }}" class="dato-valor">{{ $correo }}</a>
                        @else
                            <span class="dato-valor">[pendiente]</span>
                        @endif
                    </li>
                    <li>
                        <span class="dato-etiqueta">Horario de oficina</span>
                        @forelse ($oficina as $linea)
                            <span class="dato-valor">{{ $linea }}</span>
                        @empty
                            <span class="dato-valor">[pendiente]</span>
                        @endforelse
                    </li>
                </ul>

                <a href="{{ route('horarios') }}" class="enlace">Ver horarios de misa y confesión</a>
            </div>
        </div>
    </section>
@endsection
