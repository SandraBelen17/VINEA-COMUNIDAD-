<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Tabla `rol` (id_rol, nombre_rol, permisos, funciones).
 * Roles: Administrador, Editor, Cliente.
 */
class Rol extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    protected $fillable = ['nombre_rol', 'permisos', 'funciones'];

    public const ADMINISTRADOR = 'Administrador';
    public const EDITOR = 'Editor';
    public const CLIENTE = 'Cliente';

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'rol_usuarios', 'rol_id', 'user_id');
    }
}
