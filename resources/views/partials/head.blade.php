<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('titulo', 'Inicio') · Parroquia María Madre de Dios</title>
<link rel="icon" type="image/png" href="{{ asset('img/logo-maria.png') }}">

{{-- Fuentes: para cambiarlas, cambia este enlace y las variables --fuente-* de vinea.css --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rethink+Sans:wght@400;500;600;700&family=Spectral:ital,wght@0,400;0,500;0,600;1,400&display=swap">

<link rel="stylesheet" href="{{ asset('css/vinea.css') }}">
<script src="{{ asset('js/lucide.min.js') }}" defer></script>
<script src="{{ asset('js/vinea.js') }}" defer></script>
