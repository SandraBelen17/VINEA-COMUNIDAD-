@extends('layouts.panel')

@section('titulo', 'Eventos')

@php
    $estados = \App\Models\Evento::ESTADOS;

    // Valores del formulario: lo que se escribió (si hubo error) o el evento que se edita
    $valor = function (string $campo) use ($editando) {
        $actual = $editando?->$campo;

        // En la BD la hora es "19:00:00"; el <input type="time"> espera "19:00"
        if ($actual && in_array($campo, ['hora_inicio', 'hora_fin'])) {
            $actual = substr($actual, 0, 5);
        }

        return old($campo, $actual);
    };
@endphp

@section('contenido')
    <div class="panel-titulo">
        <div>
            <h1>Eventos</h1>
            <p>Lo que publiques aquí aparece en el calendario del sitio.</p>
        </div>
        @unless ($editando)
            <a href="#formulario-evento" class="boton">Nuevo evento</a>
        @endunless
    </div>

    <div class="tabla-desliza">
        <table class="tabla">
            <thead>
                <tr>
                    <th scope="col">Título</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Hora</th>
                    <th scope="col">Lugar</th>
                    <th scope="col">Estado</th>
                    <th scope="col"><span class="solo-lector">Acciones</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($eventos as $e)
                    <tr @if ($editando?->is($e)) class="fila-activa" @endif>
                        <td>{{ $e->titulo }}</td>
                        <td class="sin-cortar">{{ $e->fecha_texto }}</td>
                        <td class="sin-cortar">{{ $e->hora_texto ?? '—' }}</td>
                        <td>{{ $e->lugar ?: '—' }}</td>
                        <td><span class="estado {{ $e->estado_clase }}">{{ $e->estado_texto }}</span></td>
                        <td>
                            <div class="tabla-acciones">
                                <a href="{{ route('panel.eventos.editar', $e) }}#formulario-evento" class="enlace">Editar</a>
                                <form method="POST" action="{{ route('panel.eventos.eliminar', $e) }}"
                                      onsubmit="return confirm(@js('¿Eliminar el evento «' . $e->titulo . '»? No se puede deshacer.'));">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="boton-texto enlace enlace-peligro">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="tabla-vacia">Todavía no hay eventos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <section class="caja-formulario" id="formulario-evento" aria-labelledby="titulo-formulario">
        <h2 id="titulo-formulario">{{ $editando ? 'Editar evento' : 'Nuevo evento' }}</h2>

        <form method="POST" novalidate
              action="{{ $editando ? route('panel.eventos.actualizar', $editando) : route('panel.eventos.guardar') }}">
            @csrf
            @if ($editando) @method('PUT') @endif

            <div class="campos-fila">
                <div class="campo">
                    <label for="titulo">Título</label>
                    <input id="titulo" name="titulo" type="text" maxlength="150" value="{{ $valor('titulo') }}" placeholder="Nombre del evento" required>
                    @error('titulo') <span class="error-campo">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="lugar">Lugar</label>
                    <input id="lugar" name="lugar" type="text" maxlength="255" value="{{ $valor('lugar') }}" placeholder="Templo parroquial">
                    @error('lugar') <span class="error-campo">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="campo">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="4" maxlength="2000" placeholder="De qué trata el evento">{{ $valor('descripcion') }}</textarea>
                @error('descripcion') <span class="error-campo">{{ $message }}</span> @enderror
            </div>

            <div class="campos-fila">
                <div class="campo">
                    <label for="fecha_inicio">Fecha de inicio</label>
                    <input id="fecha_inicio" name="fecha_inicio" type="date" value="{{ $valor('fecha_inicio') }}" required>
                    @error('fecha_inicio') <span class="error-campo">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="fecha_fin">Fecha de fin</label>
                    <input id="fecha_fin" name="fecha_fin" type="date" value="{{ $valor('fecha_fin') }}">
                    @error('fecha_fin') <span class="error-campo">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="hora_inicio">Hora de inicio</label>
                    <input id="hora_inicio" name="hora_inicio" type="time" value="{{ $valor('hora_inicio') }}">
                    @error('hora_inicio') <span class="error-campo">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="hora_fin">Hora de fin</label>
                    <input id="hora_fin" name="hora_fin" type="time" value="{{ $valor('hora_fin') }}">
                    @error('hora_fin') <span class="error-campo">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="campos-fila">
                <div class="campo">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado" required>
                        @foreach ($estados as $clave => [$texto, $clase])
                            <option value="{{ $clave }}" @selected(($valor('estado') ?? 'publicado') === $clave)>{{ $texto }}</option>
                        @endforeach
                    </select>
                    @error('estado') <span class="error-campo">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="botones">
                <button type="submit" class="boton">{{ $editando ? 'Guardar cambios' : 'Guardar evento' }}</button>
                <a href="{{ route('panel.eventos') }}" class="boton boton-linea">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
