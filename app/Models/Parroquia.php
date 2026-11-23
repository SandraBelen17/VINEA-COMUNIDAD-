<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Tabla `parroquia` (id_parroquia, nombre, descripcion, correo, telefono, direccion,
 * logo, fecha_actualizacion). Tiene un solo registro: los datos que salen en el pie
 * de página y en /contacto. El logo es fijo (public/img/logo-maria.png), no se edita.
 */
class Parroquia extends Model
{
    protected $table = 'parroquia';
    protected $primaryKey = 'id_parroquia';
    public $timestamps = false; // la tabla no tiene created_at ni updated_at

    protected $fillable = ['nombre', 'descripcion', 'correo', 'telefono', 'direccion', 'fecha_actualizacion'];

    /** El único registro (o null si todavía no se ha capturado). */
    public static function actual(): ?self
    {
        return self::orderBy('id_parroquia')->first();
    }

    /** "2381392082" → "238 139 2082" */
    public function getTelefonoTextoAttribute(): ?string
    {
        return Contacto::formatoTelefono($this->telefono);
    }

    /** Solo los dígitos, para el enlace tel: */
    public function getTelefonoEnlaceAttribute(): ?string
    {
        return $this->telefono ? preg_replace('/\D/', '', $this->telefono) : null;
    }

    /** "22 nov 2026 · 18:05" o null */
    public function getActualizacionTextoAttribute(): ?string
    {
        if (! $this->fecha_actualizacion) {
            return null;
        }

        $fecha = Carbon::parse($this->fecha_actualizacion);

        return $fecha->day . ' ' . mb_strtolower(Evento::MESES_CORTOS[$fecha->month]) . ' ' . $fecha->year
            . ' · ' . $fecha->format('G:i');
    }
}
