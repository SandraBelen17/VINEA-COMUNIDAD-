@extends('layouts.panel')

@section('titulo', 'Mis citas')

@section('contenido')
    <div class="panel-titulo">
        <div>
            <h1>Mis citas</h1>
            <p>Pide una cita con el párroco y revisa su estado.</p>
        </div>
    </div>

    <section class="bloque-citas" aria-labelledby="titulo-pedir">
        <h2 id="titulo-pedir">Pedir una cita</h2>

        <form method="POST" action="{{ route('mis-citas.pedir') }}" class="formulario" novalidate>
            @csrf

            <div class="campos-fila">
                <div class="campo">
                    <label for="fecha">Fecha</label>
                    <input id="fecha" name="fecha" type="date" value="{{ old('fecha') }}"
                           min="{{ now()->toDateString() }}" max="{{ now()->addYear()->toDateString() }}" required>
                    @error('fecha') <span class="error-campo">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="hora">Hora</label>
                    <input id="hora" name="hora" type="time" value="{{ old('hora') }}" required>
                    @error('hora') <span class="error-campo">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="tipo_cita">Tipo de cita</label>
                    <select id="tipo_cita" name="tipo_cita" required>
                        @foreach (\App\Models\Cita::TIPOS as $clave => $nombre)
                            <option value="{{ $clave }}" @selected(old('tipo_cita') === $clave)>{{ $nombre }}</option>
                        @endforeach
                    </select>
                    @error('tipo_cita') <span class="error-campo">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="campo">
                <label for="motivo">Motivo</label>
                <textarea id="motivo" name="motivo" rows="4" maxlength="1000" placeholder="Cuéntanos brevemente el motivo" required>{{ old('motivo') }}</textarea>
                @error('motivo') <span class="error-campo">{{ $message }}</span> @enderror
            </div>

            <p class="texto-suave nota-chica">Tu cita queda pendiente hasta que el párroco la apruebe.</p>

            <div>
                <button type="submit" class="boton">Enviar solicitud</button>
            </div>
        </form>
    </section>

    <section class="bloque-citas bloque-separado" aria-labelledby="titulo-tus-citas">
        <h2 id="titulo-tus-citas">Tus citas</h2>

        @if ($citas->isEmpty())
            <p class="lista-vacia texto-suave">Todavía no has pedido ninguna cita.</p>
        @else
            <ul class="lista-citas">
                @foreach ($citas as $c)
                    <li>
                        <span class="cita-datos">
                            <span class="cita-tipo">{{ $c->tipo_texto }}</span>
                            <span class="cita-cuando">{{ $c->fecha_texto }} · {{ $c->hora_texto }}</span>
                        </span>
                        <span class="estado {{ $c->estado_clase }}">{{ $c->estado_texto }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
@endsection
