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
