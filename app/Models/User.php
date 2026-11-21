<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Tabla `users` (estructura estándar de Laravel).
 * Los roles se guardan en la tabla intermedia `rol_usuarios` (user_id, rol_id).
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // al guardar, Laravel aplica hash (bcrypt) automáticamente
        ];
    }

    /** Roles del usuario (rol_usuarios no tiene fechas, por eso no se usa withTimestamps). */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'rol_usuarios', 'user_id', 'rol_id');
    }

    /** ¿Tiene alguno de estos roles? Ej.: $user->tieneRol('Administrador', 'Editor') */
    public function tieneRol(string ...$roles): bool
    {
        return $this->roles->pluck('nombre_rol')->intersect($roles)->isNotEmpty();
    }

    public function esAdmin(): bool
    {
        return $this->tieneRol(Rol::ADMINISTRADOR);
    }

    public function esEditor(): bool
    {
        return $this->tieneRol(Rol::EDITOR);
    }

    public function esCliente(): bool
    {
        return $this->tieneRol(Rol::CLIENTE);
    }

    /** Nombre del rol principal para mostrar en el panel. */
    public function getRolPrincipalAttribute(): string
    {
        foreach ([Rol::ADMINISTRADOR, Rol::EDITOR, Rol::CLIENTE] as $rol) {
            if ($this->tieneRol($rol)) {
                return $rol;
            }
        }

        return 'Sin rol';
    }

    /** Página a la que va cada rol después de iniciar sesión. */
    public function rutaInicio(): string
    {
        return $this->tieneRol(Rol::ADMINISTRADOR, Rol::EDITOR)
            ? route('panel.resumen')
            : route('mis-citas');
    }
}
