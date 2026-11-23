<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Tabla `contacto` (id_contacto, nombre, correo, telefono, asunto, mensaje, estado, fecha_envio).
 * Mensajes que llegan desde /contacto. Solo el Administrador los ve en /panel/mensajes.
 * No se guarda quién lo atendió (la relación "Atiende" con users se quitó del diseño).
 */
class Contacto extends Model
{
    protected $table = 'contacto';
    protected $primaryKey = 'id_contacto';
    public $timestamps = false; // la tabla no tiene created_at ni updated_at

    protected $fillable = ['nombre', 'correo', 'telefono', 'asunto', 'mensaje', 'estado', 'fecha_envio'];

    /** Estados del ENUM: [texto, nombre de la pestaña del panel, clase de la etiqueta]. */
    public const ESTADOS = [
        'pendiente' => ['Pendiente', 'Sin atender', 'estado-aviso'],
        'atendido' => ['Atendido', 'Atendidos', 'estado-ok'],
    ];

    /** Pendientes primero; dentro de cada grupo, el más reciente arriba. */
    public function scopePendientesPrimero($consulta)
    {
        return $consulta->orderByRaw("FIELD(estado, 'pendiente', 'atendido')")
            ->orderByDesc('fecha_envio')
            ->orderByDesc('id_contacto');
    }

    public function getEstadoTextoAttribute(): string
    {
        return self::ESTADOS[$this->estado][0] ?? $this->estado;
    }

    public function getEstadoClaseAttribute(): string
    {
        return self::ESTADOS[$this->estado][2] ?? 'estado-neutro';
    }

    /** "22 nov 2026 · 18:05" */
    public function getFechaTextoAttribute(): string
    {
        if (! $this->fecha_envio) {
            return '';
        }

        $fecha = Carbon::parse($this->fecha_envio);

        return $fecha->day . ' ' . mb_strtolower(Evento::MESES_CORTOS[$fecha->month]) . ' ' . $fecha->year
            . ' · ' . $fecha->format('G:i');
    }

    /** Enlace para responder desde el programa de correo, con el asunto ya puesto. */
    public function getEnlaceResponderAttribute(): string
    {
        return 'mailto:' . $this->correo . '?subject=' . rawurlencode('Re: ' . $this->asunto);
    }

    /** "2381392082" → "238 139 2082" (si no son 10 dígitos, se deja como se escribió) */
    public static function formatoTelefono(?string $telefono): ?string
    {
        if (! $telefono) {
            return null;
        }

        $digitos = preg_replace('/\D/', '', $telefono);

        return strlen($digitos) === 10
            ? substr($digitos, 0, 3) . ' ' . substr($digitos, 3, 3) . ' ' . substr($digitos, 6)
            : $telefono;
    }
}
