<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Tabla `intenciones` (id_intenciones, user_id, contenido, tipo_intencion, estado, fecha).
 * Se envían desde /muro (con o sin cuenta; sin cuenta, user_id queda vacío) y
 * entran como "pendiente". Solo las "publicada" salen en el muro.
 */
class Intencion extends Model
{
    protected $table = 'intenciones';
    protected $primaryKey = 'id_intenciones';
    public $timestamps = false; // la tabla no tiene created_at ni updated_at

    protected $fillable = ['user_id', 'contenido', 'tipo_intencion', 'estado', 'fecha'];

    /** Tipos del ENUM: [texto, ícono de Lucide]. */
    public const TIPOS = [
        'oracion' => ['Oración', 'hand-heart'],
        'velita' => ['Velita', 'flame'],
    ];

    /** Estados del ENUM con el nombre de su pestaña en el panel. */
    public const ESTADOS = [
        'pendiente' => 'Por revisar',
        'publicada' => 'Publicadas',
        'rechazada' => 'Rechazadas',
    ];

    /** Quién la envió (vacío si fue sin cuenta). */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTipoTextoAttribute(): string
    {
        return self::TIPOS[$this->tipo_intencion][0] ?? $this->tipo_intencion;
    }

    public function getTipoIconoAttribute(): string
    {
        return self::TIPOS[$this->tipo_intencion][1] ?? 'hand-heart';
    }

    /** "20 nov 2026" (como en el mockup) */
    public function getFechaTextoAttribute(): string
    {
        if (! $this->fecha) {
            return '';
        }

        $fecha = Carbon::parse($this->fecha);

        return $fecha->day . ' ' . mb_strtolower(Evento::MESES_CORTOS[$fecha->month]) . ' ' . $fecha->year;
    }

    /** Panel: nombre de quien la envió, o "Anónimo" */
    public function getAutorTextoAttribute(): string
    {
        return $this->usuario?->name ?? 'Anónimo';
    }
}
