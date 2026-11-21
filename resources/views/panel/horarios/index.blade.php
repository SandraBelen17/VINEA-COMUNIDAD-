@extends('layouts.panel')

@section('titulo', 'Horarios')

@php
    $dias = \App\Models\Horario::DIAS;
    $tipos = \App\Models\Horario::TIPOS;

    // Valores del formulario: lo que se escribió (si hubo error) o el horario que se edita
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
            <h1>Horarios</h1>
            <p>Cambia aquí los horarios de misa, confesión y oficina.</p>
        </div>
        @unless ($editando)
            <a href="#formulario-horario" class="boton">Nuevo horario</a>
        @endunless
    </div>

    <nav class="filtros" aria-label="Filtrar por tipo">
        <a href="{{ route('panel.horarios') }}" @if (! $filtro) aria-current="page" @endif>Todos</a>
        @foreach ($tipos as $clave => [$singular, $plural])
            <a href="{{ route('panel.horarios', ['tipo' => $clave]) }}" @if ($filtro === $clave) aria-current="page" @endif>{{ $plural }}</a>
        @endforeach
    </nav>

    <div class="tabla-desliza">
        <table class="tabla">
            <thead>
                <tr>
                    <th scope="col">Tipo</th>
                    <th scope="col">Día</th>
                    <th scope="col">Hora de inicio</th>
                    <th scope="col">Hora de fin</th>
                    <th scope="col"><span class="solo-lector">Acciones</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($horarios as $h)
                    <tr @if ($editando?->is($h)) class="fila-activa" @endif>
                        <td>{{ $h->tipo_texto }}</td>
                        <td>{{ $h->dia_texto }}</td>
                        <td>{{ $h->inicio_texto }}</td>
                        <td>{{ $h->fin_texto }}</td>
                        <td>
                            <div class="tabla-acciones">
                                <a href="{{ route('panel.horarios.editar', ['horario' => $h, 'tipo' => $filtro]) }}#formulario-horario" class="enlace">Editar</a>
                                <form method="POST" action="{{ route('panel.horarios.eliminar', $h) }}"
                                      onsubmit="return confirm('¿Eliminar el horario de {{ mb_strtolower($h->tipo_texto) }} del {{ mb_strtolower($h->dia_texto) }} a las {{ $h->inicio_texto }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="boton-texto enlace enlace-peligro">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="tabla-vacia">No hay horarios registrados{{ $filtro ? ' de este tipo' : '' }}.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <section class="caja-formulario" id="formulario-horario" aria-labelledby="titulo-formulario">
        <h2 id="titulo-formulario">{{ $editando ? 'Editar horario' : 'Nuevo horario' }}</h2>

        <form method="POST" novalidate
              action="{{ $editando ? route('panel.horarios.actualizar', $editando) : route('panel.horarios.guardar') }}">
            @csrf
            @if ($editando) @method('PUT') @endif

            <div class="campos-fila">
                <div class="campo">
                    <label for="tipo">Tipo</label>
                    <select id="tipo" name="tipo" required>
                        @foreach ($tipos as $clave => [$singular, $plural])
                            <option value="{{ $clave }}" @selected($valor('tipo') === $clave || (! $valor('tipo') && $filtro === $clave))>{{ $singular }}</option>
                        @endforeach
                    </select>
                    @error('tipo') <span class="error-campo">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="dia">Día</label>
                    <select id="dia" name="dia" required>
                        @foreach ($dias as $clave => $nombre)
                            <option value="{{ $clave }}" @selected($valor('dia') === $clave)>{{ $nombre }}</option>
                        @endforeach
                    </select>
                    @error('dia') <span class="error-campo">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="hora_inicio">Hora de inicio</label>
                    <input id="hora_inicio" name="hora_inicio" type="time" value="{{ $valor('hora_inicio') }}" required>
                    @error('hora_inicio') <span class="error-campo">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="hora_fin">Hora de fin</label>
                    <input id="hora_fin" name="hora_fin" type="time" value="{{ $valor('hora_fin') }}" required>
                    @error('hora_fin') <span class="error-campo">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="botones">
                <button type="submit" class="boton">{{ $editando ? 'Guardar cambios' : 'Guardar horario' }}</button>
                @if ($editando)
                    <a href="{{ route('panel.horarios', ['tipo' => $filtro]) }}" class="boton boton-linea">Cancelar</a>
                @else
                    <button type="reset" class="boton boton-linea">Limpiar</button>
                @endif
            </div>
        </form>
    </section>
@endsection
