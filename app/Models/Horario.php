<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Tabla `horarios` (id_horario, id_parroquia, dia, hora_inicio, hora_fin, tipo).
 * Solo guarda los horarios del templo parroquial: misas, confesiones y oficina.
 * Las misas de las capillas van como texto fijo en la vista (la tabla no tiene lugar).
 */
class Horario extends Model
{
    protected $table = 'horarios';
    protected $primaryKey = 'id_horario';
    public $timestamps = false; // la tabla no tiene created_at ni updated_at

    protected $fillable = ['id_parroquia', 'dia', 'hora_inicio', 'hora_fin', 'tipo'];

    /** Días en el orden de la semana. Clave = valor del ENUM; valor = cómo se muestra. */
    public const DIAS = [
        'lunes' => 'Lunes',
        'martes' => 'Martes',
        'miercoles' => 'Miércoles',
        'jueves' => 'Jueves',
        'viernes' => 'Viernes',
        'sabado' => 'Sábado',
        'domingo' => 'Domingo',
    ];

    /** Abreviaturas para las fichas de la semana. */
    public const DIAS_CORTOS = [
        'lunes' => 'Lun',
        'martes' => 'Mar',
        'miercoles' => 'Mié',
        'jueves' => 'Jue',
        'viernes' => 'Vie',
        'sabado' => 'Sáb',
        'domingo' => 'Dom',
    ];

    /** Tipos del ENUM: [singular, plural]. */
    public const TIPOS = [
        'misa' => ['Misa', 'Misas'],
        'confesion' => ['Confesión', 'Confesiones'],
        'oficina' => ['Oficina', 'Oficina'],
    ];

    /** Ordena de lunes a domingo y luego por hora. */
    public function scopeOrdenados(Builder $consulta): Builder
    {
        $dias = "'" . implode("','", array_keys(self::DIAS)) . "'";

        return $consulta->orderByRaw("FIELD(dia, $dias)")->orderBy('hora_inicio');
    }

    /** Día de hoy como valor del ENUM (con la hora de México). */
    public static function diaDeHoy(): string
    {
        $numero = Carbon::now('America/Mexico_City')->dayOfWeekIso; // 1 = lunes ... 7 = domingo

        return array_keys(self::DIAS)[$numero - 1];
    }

    /**
     * Resumen de un tipo de horario en pocas líneas, juntando los días seguidos con
     * las mismas horas. Ej. oficina: ["Martes a domingo · 3:00 pm – 7:00 pm"].
     * Se usa en /contacto. Si no hay horarios, regresa [].
     */
    public static function resumen(string $tipo): array
    {
        // Horas de cada día: ['martes' => '3:00 pm – 7:00 pm', ...]
        $porDia = self::where('tipo', $tipo)->ordenados()->get()
            ->groupBy('dia')
            ->map(fn ($horarios) => $horarios
                ->map(fn ($h) => self::formatoHora($h->hora_inicio) . ' – ' . self::formatoHora($h->hora_fin))
                ->implode(' y '));

        // Juntar días seguidos (en orden de la semana) que tienen las mismas horas
        $grupos = [];
        foreach (array_keys(self::DIAS) as $dia) {
            $horas = $porDia[$dia] ?? null;
            $ultimo = count($grupos) - 1;

            if ($horas && $ultimo >= 0 && $grupos[$ultimo]['horas'] === $horas && $grupos[$ultimo]['seguido']) {
                $grupos[$ultimo]['dias'][] = $dia;
            } elseif ($horas) {
                $grupos[] = ['dias' => [$dia], 'horas' => $horas, 'seguido' => true];
            } elseif ($ultimo >= 0) {
                $grupos[$ultimo]['seguido'] = false; // un día sin horario corta el grupo
            }
        }

        return array_map(function ($grupo) {
            $dias = $grupo['dias'];
            $primero = self::DIAS[$dias[0]];
            $ultimo = mb_strtolower(self::DIAS[end($dias)]);

            $texto = match (count($dias)) {
                1 => $primero,
                2 => "$primero y $ultimo",
                default => "$primero a $ultimo",
            };

            return $texto . ' · ' . $grupo['horas'];
        }, $grupos);
    }

    /** "19:00:00" → "7:00 pm" */
    public static function formatoHora(string $hora): string
    {
        return Carbon::createFromFormat('H:i:s', $hora)->format('g:i a');
    }

    public function getDiaTextoAttribute(): string
    {
        return self::DIAS[$this->dia] ?? $this->dia;
    }

    public function getTipoTextoAttribute(): string
    {
        return self::TIPOS[$this->tipo][0] ?? $this->tipo;
    }

    public function getInicioTextoAttribute(): string
    {
        return self::formatoHora($this->hora_inicio);
    }

    public function getFinTextoAttribute(): string
    {
        return self::formatoHora($this->hora_fin);
    }
}
