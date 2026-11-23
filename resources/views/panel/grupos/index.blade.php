@extends('layouts.panel')

@section('titulo', 'Grupos')

@php
    $estados = \App\Models\Grupo::ESTADOS;

    // Valores del formulario: lo que se escribió (si hubo error) o el grupo que se edita
    $valor = fn (string $campo) => old($campo, $editando?->$campo);
@endphp

@section('contenido')
    <div class="panel-titulo">
        <div>
            <h1>Grupos</h1>
            <p>Solo los grupos activos aparecen en el sitio.</p>
        </div>
        @unless ($editando)
            <a href="#formulario-grupo" class="boton">Nuevo grupo</a>
        @endunless
    </div>

    <div class="tabla-desliza">
        <table class="tabla">
            <thead>
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Horario</th>
                    <th scope="col">Lugar</th>
                    <th scope="col">Estado</th>
                    <th scope="col"><span class="solo-lector">Acciones</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($grupos as $g)
                    <tr @if ($editando?->is($g)) class="fila-activa" @endif>
                        <td>{{ $g->nombre }}</td>
                        <td>{{ $g->horario ?: '—' }}</td>
                        <td>{{ $g->lugar ?: '—' }}</td>
                        <td><span class="estado {{ $g->estado_clase }}">{{ $g->estado_texto }}</span></td>
                        <td>
                            <div class="tabla-acciones">
                                <a href="{{ route('panel.grupos.editar', $g) }}#formulario-grupo" class="enlace">Editar</a>
                                <form method="POST" action="{{ route('panel.grupos.eliminar', $g) }}"
                                      onsubmit="return confirm(@js('¿Eliminar "' . $g->nombre . '"? No se puede deshacer.'));">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="boton-texto enlace enlace-peligro">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="tabla-vacia">Todavía no hay grupos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <section class="caja-formulario" id="formulario-grupo" aria-labelledby="titulo-formulario">
        <h2 id="titulo-formulario">{{ $editando ? 'Editar grupo' : 'Nuevo grupo' }}</h2>

        <form method="POST" novalidate
              action="{{ $editando ? route('panel.grupos.actualizar', $editando) : route('panel.grupos.guardar') }}">
            @csrf
            @if ($editando) @method('PUT') @endif

            <div class="campos-fila">
                <div class="campo">
                    <label for="nombre">Nombre</label>
                    <input id="nombre" name="nombre" type="text" maxlength="150" value="{{ $valor('nombre') }}" placeholder="Nombre del grupo" required>
                    @error('nombre') <span class="error-campo">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="horario">Horario</label>
                    <input id="horario" name="horario" type="text" maxlength="150" value="{{ $valor('horario') }}" placeholder="Sábados 16:00 horas">
                    @error('horario') <span class="error-campo">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="lugar">Lugar</label>
                    <input id="lugar" name="lugar" type="text" maxlength="255" value="{{ $valor('lugar') }}" placeholder="Salón parroquial">
                    @error('lugar') <span class="error-campo">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="campo">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="4" maxlength="2000" placeholder="A quién va dirigido y qué hacen">{{ $valor('descripcion') }}</textarea>
                @error('descripcion') <span class="error-campo">{{ $message }}</span> @enderror
            </div>

            <div class="campos-fila">
                <div class="campo">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado" required>
                        @foreach ($estados as $clave => [$texto, $clase])
                            <option value="{{ $clave }}" @selected(($valor('estado') ?? 'activo') === $clave)>{{ $texto }}</option>
                        @endforeach
                    </select>
                    @error('estado') <span class="error-campo">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="botones">
                <button type="submit" class="boton">{{ $editando ? 'Guardar cambios' : 'Guardar grupo' }}</button>
                <a href="{{ route('panel.grupos') }}" class="boton boton-linea">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
