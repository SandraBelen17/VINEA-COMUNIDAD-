<!DOCTYPE html>
<html lang="es">
<head>
    @include('partials.head')
</head>
<body>
<div class="pagina">

    <header class="encabezado">
        <div class="encabezado-fila">
            <a href="{{ route('inicio') }}" class="marca">
                <img src="{{ asset('img/logo-maria.png') }}" alt="">
                <span class="marca-texto">
                    <span class="marca-nombre">María Madre de Dios</span>
                    <span class="marca-sub">Parroquia · Tehuacán, Puebla</span>
                </span>
            </a>

            <button type="button" class="boton-menu" aria-controls="menu-principal" aria-expanded="false">
                <i data-lucide="menu"></i><span class="solo-lector">Abrir menú</span>
            </button>

            <nav id="menu-principal" class="menu-principal" aria-label="Principal">
                <a href="{{ route('inicio') }}#parroquia">Parroquia</a>
                <a href="{{ route('horarios') }}" @if (request()->routeIs('horarios')) aria-current="page" @endif>Horarios</a>
                <a href="{{ route('inicio') }}#capillas">Capillas</a>
                <a href="{{ route('inicio') }}#sacramentos">Sacramentos</a>
                <a href="{{ route('calendario') }}" @if (request()->routeIs('calendario')) aria-current="page" @endif>Calendario</a>
                <a href="{{ route('grupos') }}" @if (request()->routeIs('grupos')) aria-current="page" @endif>Grupos</a>
                <a href="{{ route('muro') }}" @if (request()->routeIs('muro')) aria-current="page" @endif>Muro de oraciones</a>
                <a href="{{ route('contacto') }}" @if (request()->routeIs('contacto')) aria-current="page" @endif>Contacto</a>
                @auth
                    <a href="{{ auth()->user()->rutaInicio() }}" class="menu-cuenta">Mi panel</a>
                @else
                    <a href="{{ route('login') }}" class="menu-cuenta">Iniciar sesión</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="contenido">
        @yield('contenido')
    </main>

    <footer class="pie">
        <div class="pie-contenido">
            <div class="pie-columnas">
                <div class="pie-marca">
                    <img src="{{ asset('img/logo-maria.png') }}" alt="">
                    <div class="pie-marca-texto">
                        <span class="pie-titulo">{{ $parroquia?->nombre ?? 'Parroquia María Madre de Dios' }}</span>
                        <span>Diócesis de Tehuacán, Puebla</span>
                    </div>
                </div>
                {{-- Dirección, teléfono y correo: tabla parroquia (se editan en /panel/parroquia) --}}
                <div class="pie-lista">
                    @if ($parroquia?->direccion)
                        <span>{{ $parroquia->direccion }}</span>
                    @endif
                    @if ($parroquia?->telefono)
                        <a href="tel:{{ $parroquia->telefono_enlace }}">{{ $parroquia->telefono_texto }}</a>
                    @endif
                    @if ($parroquia?->correo)
                        <a href="mailto:{{ $parroquia->correo }}">{{ $parroquia->correo }}</a>
                    @endif
                </div>
                <div class="pie-lista">
                    <a href="{{ route('horarios') }}">Horarios</a>
                    <a href="{{ route('calendario') }}">Calendario</a>
                    <a href="{{ route('muro') }}">Muro de oraciones</a>
                    <a href="{{ route('contacto') }}">Contacto</a>
                </div>
            </div>
            <div class="pie-final">© {{ date('Y') }} {{ $parroquia?->nombre ?? 'Parroquia María Madre de Dios' }}</div>
        </div>
    </footer>

</div>
</body>
</html>
