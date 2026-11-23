@extends('layouts.panel')

@section('titulo', 'Usuarios')

@php
    // ¿Se está editando la propia cuenta? Entonces el rol queda fijo en Administrador.
    $editandoMiCuenta = $editando?->is($yo);

    $valor = fn (string $campo) => old($campo, $editando?->$campo);
    $rolActual = old('rol', $editando?->rol_principal ?? \App\Models\Rol::CLIENTE);
@endphp

@section('contenido')
    <div class="panel-titulo">
        <div>
            <h1>Usuarios</h1>
            <p>Quién entra al panel y qué puede hacer.</p>
        </div>
        @unless ($editando)
            <a href="#formulario-usuario" class="boton">Nuevo usuario</a>
        @endunless
    </div>

    <div class="tabla-desliza">
        <table class="tabla">
            <thead>
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Rol</th>
                    <th scope="col"><span class="solo-lector">Acciones</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $u)
                    <tr @if ($editando?->is($u)) class="fila-activa" @endif>
                        <td>{{ $u->name }}@if ($u->is($yo)) <span class="texto-suave">(tú)</span>@endif</td>
                        <td>{{ $u->email }}</td>
                        <td><span class="estado estado-neutro">{{ $u->rol_principal }}</span></td>
                        <td>
                            <div class="tabla-acciones">
                                <a href="{{ route('panel.usuarios.editar', $u) }}#formulario-usuario" class="enlace">Editar</a>
                                @unless ($u->is($yo))
                                    <form method="POST" action="{{ route('panel.usuarios.eliminar', $u) }}"
                                          onsubmit="return confirm(@js('¿Eliminar a «' . $u->name . '» (' . $u->email . ')? No se puede deshacer.'));">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="boton-texto enlace enlace-peligro">Eliminar</button>
                                    </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('partials.paginas', ['paginas' => $usuarios, 'anterior' => 'Página anterior', 'siguiente' => 'Página siguiente'])

    <section class="caja-formulario" id="formulario-usuario" aria-labelledby="titulo-formulario">
        <h2 id="titulo-formulario">{{ $editando ? 'Editar usuario' : 'Nuevo usuario' }}</h2>

        <form method="POST" novalidate
              action="{{ $editando ? route('panel.usuarios.actualizar', $editando) : route('panel.usuarios.guardar') }}">
            @csrf
            @if ($editando) @method('PUT') @endif

            <div class="campos-fila">
                <div class="campo">
                    <label for="name">Nombre</label>
                    <input id="name" name="name" type="text" maxlength="150" value="{{ $valor('name') }}" placeholder="Nombre completo" required>
                    @error('name') <span class="error-campo">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="email">Correo</label>
                    <input id="email" name="email" type="email" maxlength="150" value="{{ $valor('email') }}" placeholder="correo@ejemplo.com" autocomplete="off" required>
                    @error('email') <span class="error-campo">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="password">{{ $editando ? 'Nueva contraseña' : 'Contraseña' }}</label>
                    <input id="password" name="password" type="password" autocomplete="new-password"
                           placeholder="{{ $editando ? 'Déjala vacía para no cambiarla' : 'Mínimo 8 caracteres' }}" @unless ($editando) required @endunless>
                    @error('password') <span class="error-campo">{{ $message }}</span> @enderror
                </div>

                <div class="campo">
                    <label for="rol">Rol</label>
                    @if ($editandoMiCuenta)
                        {{-- Tu propio rol no se puede cambiar (también se revisa en el controlador) --}}
                        <input type="hidden" name="rol" value="{{ \App\Models\Rol::ADMINISTRADOR }}">
                        <select id="rol" disabled aria-describedby="rol-nota">
                            <option>Administrador</option>
                        </select>
                        <span class="nota-campo texto-suave" id="rol-nota">No puedes quitarte el rol de Administrador.</span>
                    @else
                        <select id="rol" name="rol" required>
                            @foreach ($roles as $clave => $texto)
                                <option value="{{ $clave }}" @selected($rolActual === $clave)>{{ $texto }}</option>
                            @endforeach
                        </select>
                    @endif
                    @error('rol') <span class="error-campo">{{ $message }}</span> @enderror
                </div>
            </div>

            <p class="texto-suave nota-chica">La contraseña se guarda cifrada.</p>

            <div class="botones">
                <button type="submit" class="boton">{{ $editando ? 'Guardar cambios' : 'Guardar usuario' }}</button>
                <a href="{{ route('panel.usuarios') }}" class="boton boton-linea">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
