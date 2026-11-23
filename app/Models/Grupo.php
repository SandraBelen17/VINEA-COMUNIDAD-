<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Tabla `grupos` (id_grupos, id_parroquia, nombre, descripcion, horario, lugar,
 * estado, fecha_registro). Solo los "activo" salen en /grupos.
 * fecha_registro la llena MySQL (DEFAULT CURRENT_TIMESTAMP).
 */
class Grupo extends Model
{
    protected $table = 'grupos';
    protected $primaryKey = 'id_grupos';
    public $timestamps = false; // la tabla no tiene created_at ni updated_at

    protected $fillable = ['id_parroquia', 'nombre', 'descripcion', 'horario', 'lugar', 'estado'];

    /** Estados del ENUM: [texto, clase de la etiqueta en el panel]. */
    public const ESTADOS = [
        'activo' => ['Activo', 'estado-ok'],
        'inactivo' => ['Inactivo', 'estado-neutro'],
    ];

    /** Grupos activos por nombre (página pública). */
    public function scopeActivos(Builder $consulta): Builder
    {
        return $consulta->where('estado', 'activo')->orderBy('nombre');
    }

    public function getEstadoTextoAttribute(): string
    {
        return self::ESTADOS[$this->estado][0] ?? $this->estado;
    }

    public function getEstadoClaseAttribute(): string
    {
        return self::ESTADOS[$this->estado][1] ?? 'estado-neutro';
    }

    /** Línea "horario · lugar" con lo que haya */
    public function getDetalleTextoAttribute(): string
    {
        return collect([$this->horario, $this->lugar])->filter()->implode(' · ');
    }
}
