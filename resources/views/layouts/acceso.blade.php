<!DOCTYPE html>
<html lang="es">
<head>
    @include('partials.head')
</head>
<body>
<div class="acceso">

    <div class="acceso-foto">
        <img src="{{ asset('img/altar.jpg') }}" alt="">
        <div class="slider-velo" aria-hidden="true"></div>

        <a href="{{ route('inicio') }}" class="acceso-volver">
            <i data-lucide="chevron-left"></i> Volver al sitio
        </a>

        <div class="acceso-marca">
            <img src="{{ asset('img/logo-maria.png') }}" alt="">
            <span class="etiqueta">Diócesis de Tehuacán, Puebla</span>
            <span class="acceso-nombre">Parroquia María Madre de Dios</span>
        </div>
    </div>

    <div class="acceso-formulario">
        <div class="acceso-caja">
            @yield('contenido')
        </div>
    </div>

</div>
</body>
</html>
