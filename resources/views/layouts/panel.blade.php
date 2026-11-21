@php($usuario = auth()->user())
<!DOCTYPE html>
<html lang="es">
<head>
    @include('partials.head')
</head>
<body>
<div class="panel">

    <header class="panel-encabezado">
        <div class="panel-encabezado-fila">
            <a href="{{ route('inicio') }}" class="marca" title="Ir al sitio público">
                <img src="{{ asset('img/logo-maria.png') }}" alt="">
                <span class="marca-texto">
                    <span class="marca-nombre">María Madre de Dios</span>
                    <span class="marca-sub">Panel de la parroquia</span>
                </span>
            </a>

            <div class="panel-usuario">
                <span class="panel-usuario-datos">
                    <span class="panel-usuario-nombre">{{ $usuario->name }}</span>
                    <span class="panel-usuario-rol">{{ $usuario->rol_principal }}</span>
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="boton-texto">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </header>

    <div class="panel-cuerpo">
        <aside class="panel-lateral">
            <nav aria-label="Panel">
                <ul class="panel-menu">
                    @foreach (config('panel.menu') as $opcion)
                        @if ($usuario->tieneRol(...$opcion['roles']))
                            <li>
                                <a href="{{ route($opcion['ruta']) }}" @if (request()->routeIs($opcion['ruta'])) aria-current="page" @endif>
                                    <i data-lucide="{{ $opcion['icono'] }}"></i> {{ $opcion['texto'] }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </nav>
        </aside>

        <main class="panel-principal">
            @include('partials.avisos')
            @yield('contenido')
        </main>
    </div>

</div>
</body>
</html>
