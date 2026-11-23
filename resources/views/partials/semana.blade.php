{{-- Semana en fichas (lunes a domingo) de un tipo de horario. La usan /horarios e Inicio.
     Recibe: $tipo ('misa', 'confesion' u 'oficina'), $horas ($semana[$tipo] de Horario::semana()) y $hoy.
     Misa muestra solo la hora de inicio; confesión y oficina, inicio – fin. --}}
@php($conFin = $tipo !== 'misa')
<div class="semana-desliza">
    <ul class="semana">
        @foreach (\App\Models\Horario::DIAS as $dia => $nombreDia)
            <li class="dia @if ($dia === $hoy) dia-hoy @endif">
                @if ($dia === $hoy)
                    <span class="dia-nombre">Hoy<span class="solo-lector">, {{ $nombreDia }}</span></span>
                @else
                    <span class="dia-nombre"><span aria-hidden="true">{{ \App\Models\Horario::DIAS_CORTOS[$dia] }}</span><span class="solo-lector">{{ $nombreDia }}</span></span>
                @endif

                @forelse ($horas[$dia] as $h)
                    <span class="dia-hora">
                        <span class="dia-hora-parte">{{ $h->inicio_texto }}</span>@if ($conFin) – <span class="dia-hora-parte">{{ $h->fin_texto }}</span>@endif
                    </span>
                    @if ($tipo === 'confesion' && $dia === 'jueves' && $h->hora_inicio === '18:00:00')
                        <span class="dia-nota">Hora santa</span>
                    @endif
                @empty
                    <span class="dia-hora dia-vacio"><span aria-hidden="true">—</span><span class="solo-lector">Sin horario</span></span>
                @endforelse
            </li>
        @endforeach
    </ul>
</div>
