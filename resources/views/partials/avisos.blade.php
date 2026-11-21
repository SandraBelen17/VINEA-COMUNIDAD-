@if (session('exito'))
    <p class="aviso aviso-exito" role="status">{{ session('exito') }}</p>
@endif
@if (session('info'))
    <p class="aviso aviso-info" role="status">{{ session('info') }}</p>
@endif
