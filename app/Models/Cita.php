<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Tabla `citas` (id_cita, user_id, fecha, hora, tipo_cita, motivo, estado).
 * El Cliente (feligrés) la pide desde /mis-citas y entra como "pendiente".
 * Solo el Administrador (párroco) la aprueba o la rechaza.
 */
class Cita extends Model
{
    protected $table = 'citas';
    protected $primaryKey = 'id_cita';
    public $timestamps = false; // la tabla no tiene created_at ni updated_at

    protected $fillable = ['user_id', 'fecha', 'hora', 'tipo_cita', 'motivo', 'estado'];

    /**
     * Tipos de cita. Clave = lo que se guarda en tipo_cita (sin acentos, como los
     * datos del script 04); valor = cómo se muestra. "[Otro tipo]" del mockup está pendiente.
     */
    public const TIPOS = [
        'Orientacion' => 'Orientación',
        'Informacion' => 'Información',
        'Confesion' => 'Confesión',
    ];

    /** Estados del ENUM: [texto, nombre de la pestaña del panel, clase de la etiqueta]. */
    public const ESTADOS = [
        'pendiente' => ['Pendiente', 'Pendientes', 'estado-aviso'],
        'aprobada' => ['Aprobada', 'Aprobadas', 'estado-ok'],
        'rechazada' => ['Rechazada', 'Rechazadas', 'estado-error'],
    ];

    /** Feligrés que pidió la cita. */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTipoTextoAttribute(): string
    {
        return self::TIPOS[$this->tipo_cita] ?? $this->tipo_cita;
    }

    public function getEstadoTextoAttribute(): string
    {
        return self::ESTADOS[$this->estado][0] ?? $this->estado;
    }

    public function getEstadoClaseAttribute(): string
    {
        return self::ESTADOS[$this->estado][2] ?? 'estado-neutro';
    }

    /** "01/12/2026" */
    public function getFechaTextoAttribute(): string
    {
        return Carbon::parse($this->fecha)->format('d/m/Y');
    }

    /** "10:00:00" → "10:00" */
    public function getHoraTextoAttribute(): string
    {
        return Carbon::createFromFormat('H:i:s', $this->hora)->format('G:i');
    }
}
