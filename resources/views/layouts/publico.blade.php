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
                        <span class="pie-titulo">Parroquia María Madre de Dios</span>
                        <span>Diócesis de Tehuacán, Puebla</span>
                    </div>
                </div>
                <div class="pie-lista">
                    <span>Calle 9 Nte 2601, Santiago Tula</span>
                    <span>75720 Tehuacán, Pue.</span>
                    <a href="tel:2381392082">238 139 2082</a>
                </div>
                <div class="pie-lista">
                    <a href="{{ route('horarios') }}">Horarios</a>
                    <a href="{{ route('calendario') }}">Calendario</a>
                    <a href="{{ route('muro') }}">Muro de oraciones</a>
                    <a href="{{ route('contacto') }}">Contacto</a>
                </div>
            </div>
            <div class="pie-final">© {{ date('Y') }} Parroquia María Madre de Dios</div>
        </div>
    </footer>

</div>
</body>
</html>
