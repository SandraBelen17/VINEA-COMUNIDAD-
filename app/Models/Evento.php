<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Tabla `evento` (id_evento, user_id, id_parroquia, titulo, descripcion,
 * fecha_inicio, fecha_fin, hora_inicio, hora_fin, lugar, estado).
 * user_id = quien creó el evento. Solo los "publicado" salen en /calendario.
 */
class Evento extends Model
{
    protected $table = 'evento';
    protected $primaryKey = 'id_evento';
    public $timestamps = false; // la tabla no tiene created_at ni updated_at

    protected $fillable = [
        'user_id', 'id_parroquia', 'titulo', 'descripcion',
        'fecha_inicio', 'fecha_fin', 'hora_inicio', 'hora_fin', 'lugar', 'estado',
    ];

    /** Estados del ENUM: [texto, clase de la etiqueta en el panel]. */
    public const ESTADOS = [
        'publicado' => ['Publicado', 'estado-ok'],
        'borrador' => ['Borrador', 'estado-neutro'],
        'cancelado' => ['Cancelado', 'estado-error'],
    ];

    public const MESES = [
        1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
        'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre',
    ];

    public const MESES_CORTOS = [
        1 => 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN',
        'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC',
    ];

    /** Eventos publicados que caen en el mes (también los de varios días que lo cruzan). */
    public function scopePublicadosDelMes(Builder $consulta, Carbon $mes): Builder
    {
        $inicio = $mes->copy()->startOfMonth()->toDateString();
        $fin = $mes->copy()->endOfMonth()->toDateString();

        return $consulta->where('estado', 'publicado')
            ->where('fecha_inicio', '<=', $fin)
            ->whereRaw('COALESCE(fecha_fin, fecha_inicio) >= ?', [$inicio])
            ->orderBy('fecha_inicio')
            ->orderBy('hora_inicio');
    }

    /** Eventos publicados de hoy en adelante (incluye los de varios días que siguen en curso). */
    public function scopeProximos(Builder $consulta): Builder
    {
        return $consulta->where('estado', 'publicado')
            ->whereRaw('COALESCE(fecha_fin, fecha_inicio) >= ?', [now()->toDateString()])
            ->orderBy('fecha_inicio')
            ->orderBy('hora_inicio');
    }

    /** "Diciembre 2026" */
    public static function nombreMes(Carbon $mes): string
    {
        return ucfirst(self::MESES[$mes->month]) . ' ' . $mes->year;
    }

    /** "09:00:00" → "9:00" (como en el mockup) */
    public static function formatoHora(?string $hora): ?string
    {
        return $hora ? Carbon::createFromFormat('H:i:s', $hora)->format('G:i') : null;
    }

    /** ¿Dura más de un día? */
    public function getVariosDiasAttribute(): bool
    {
        return $this->fecha_fin && $this->fecha_fin > $this->fecha_inicio;
    }

    public function getEstadoTextoAttribute(): string
    {
        return self::ESTADOS[$this->estado][0] ?? $this->estado;
    }

    public function getEstadoClaseAttribute(): string
    {
        return self::ESTADOS[$this->estado][1] ?? 'estado-neutro';
    }

    /** Panel: "08/12/2026" o "08/12/2026 – 10/12/2026" */
    public function getFechaTextoAttribute(): string
    {
        $texto = Carbon::parse($this->fecha_inicio)->format('d/m/Y');

        return $this->varios_dias
            ? $texto . ' – ' . Carbon::parse($this->fecha_fin)->format('d/m/Y')
            : $texto;
    }

    /** "9:00 – 10:00", "9:00" o null si no tiene hora */
    public function getHoraTextoAttribute(): ?string
    {
        $inicio = self::formatoHora($this->hora_inicio);
        $fin = self::formatoHora($this->hora_fin);

        return $inicio && $fin ? "$inicio – $fin" : $inicio;
    }

    /** Calendario: "Del 8 al 10 de diciembre" (solo si dura varios días) */
    public function getRangoTextoAttribute(): ?string
    {
        if (! $this->varios_dias) {
            return null;
        }

        $inicio = Carbon::parse($this->fecha_inicio);
        $fin = Carbon::parse($this->fecha_fin);

        return $inicio->month === $fin->month
            ? "Del {$inicio->day} al {$fin->day} de " . self::MESES[$fin->month]
            : "Del {$inicio->day} de " . self::MESES[$inicio->month] . " al {$fin->day} de " . self::MESES[$fin->month];
    }

    /** Calendario: línea "hora · lugar" con lo que haya */
    public function getDetalleTextoAttribute(): string
    {
        return collect([$this->rango_texto, $this->hora_texto, $this->lugar])->filter()->implode(' · ');
    }
}
