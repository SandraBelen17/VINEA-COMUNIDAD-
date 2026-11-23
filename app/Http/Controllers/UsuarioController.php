<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Módulo Usuarios: /panel/usuarios, SOLO Administrador (rutas en el grupo rol:Administrador).
 * Cada usuario tiene un rol, guardado en rol_usuarios.
 * Protecciones: nadie se elimina a sí mismo ni se quita su rol de Administrador,
 * y siempre queda al menos un Administrador.
 */
class UsuarioController extends Controller
{
    /** Roles en el orden del formulario, con el texto del mockup. */
    public const ROLES = [
        Rol::ADMINISTRADOR => 'Administrador',
        Rol::EDITOR => 'Editor (sacristán)',
        Rol::CLIENTE => 'Cliente (feligrés)',
    ];

    public function index(Request $request): View
    {
        return $this->pantalla($request, null);
    }

    public function editar(Request $request, User $usuario): View
    {
        return $this->pantalla($request, $usuario);
    }

    public function guardar(Request $request): RedirectResponse
    {
        $datos = $this->validar($request, null);

        DB::transaction(function () use ($datos) {
            $usuario = User::create([
                'name' => $datos['name'],
                'email' => $datos['email'],
                'password' => $datos['password'], // el modelo User aplica el hash
            ]);
            $usuario->roles()->attach($this->idRol($datos['rol']));
        });

        return redirect()->route('panel.usuarios')->with('exito', 'Usuario guardado.');
    }

    public function actualizar(Request $request, User $usuario): RedirectResponse
    {
        $datos = $this->validar($request, $usuario);

        // Protecciones del rol de Administrador
        if ($usuario->esAdmin() && $datos['rol'] !== Rol::ADMINISTRADOR) {
            if ($usuario->is($request->user())) {
                throw ValidationException::withMessages(['rol' => 'No puedes quitarte tu propio rol de Administrador.']);
            }
            if ($this->totalAdministradores() <= 1) {
                throw ValidationException::withMessages(['rol' => 'Debe quedar al menos un Administrador.']);
            }
        }

        DB::transaction(function () use ($usuario, $datos) {
            $usuario->name = $datos['name'];
            $usuario->email = $datos['email'];
            if (! empty($datos['password'])) {
                $usuario->password = $datos['password']; // solo si se escribió una nueva
            }
            $usuario->save();

            $usuario->roles()->sync([$this->idRol($datos['rol'])]);
        });

        return redirect()->route('panel.usuarios')->with('exito', 'Usuario actualizado.');
    }

    public function eliminar(Request $request, User $usuario): RedirectResponse
    {
        if ($usuario->is($request->user())) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        if ($usuario->esAdmin() && $this->totalAdministradores() <= 1) {
            return back()->with('error', 'No se puede eliminar al último Administrador.');
        }

        // citas y evento no dejan borrar a un usuario con registros (llave foránea RESTRICT)
        $citas = DB::table('citas')->where('user_id', $usuario->id)->count();
        $eventos = DB::table('evento')->where('user_id', $usuario->id)->count();
        if ($citas || $eventos) {
            $tiene = collect([
                $citas ? $citas . ($citas === 1 ? ' cita' : ' citas') : null,
                $eventos ? $eventos . ($eventos === 1 ? ' evento creado' : ' eventos creados') : null,
            ])->filter()->implode(' y ');

            return back()->with('error', "No se puede eliminar a {$usuario->name}: tiene $tiene. Puedes cambiarle el rol en su lugar.");
        }

        DB::transaction(function () use ($usuario) {
            $usuario->roles()->detach(); // sus filas de rol_usuarios
            $usuario->delete();          // sus intenciones quedan como anónimas (SET NULL)
        });

        return redirect()->route('panel.usuarios')->with('exito', 'El usuario se eliminó.');
    }

    // ---------- Ayudantes ----------

    private function pantalla(Request $request, ?User $editando): View
    {
        // Primero Administradores, luego Editores y Clientes; cada grupo por nombre
        $orden = "(SELECT MIN(FIELD(r.nombre_rol, 'Administrador', 'Editor', 'Cliente'))
                   FROM rol_usuarios ru JOIN rol r ON r.id_rol = ru.rol_id
                   WHERE ru.user_id = users.id)";

        return view('panel.usuarios.index', [
            'usuarios' => User::with('roles')->orderByRaw($orden)->orderBy('name')->simplePaginate(25),
            'editando' => $editando,
            'yo' => $request->user(),
            'roles' => self::ROLES,
        ]);
    }

    private function validar(Request $request, ?User $usuario): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($usuario?->id)],
            // Al crear es obligatoria; al editar, vacía = se conserva la actual
            'password' => [$usuario ? 'nullable' : 'required', 'string', Password::min(8)],
            'rol' => ['required', Rule::in(array_keys(self::ROLES))],
        ], [
            'name.required' => 'Escribe el nombre.',
            'name.max' => 'El nombre puede tener hasta 150 caracteres.',
            'email.required' => 'Escribe el correo.',
            'email.email' => 'Escribe un correo válido.',
            'email.max' => 'El correo puede tener hasta 150 caracteres.',
            'email.unique' => 'Ya existe un usuario con este correo.',
            'password.required' => 'Escribe una contraseña.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'rol.required' => 'Elige el rol.',
            'rol.in' => 'Elige un rol de la lista.',
        ]);
    }

    private function idRol(string $nombre): int
    {
        return Rol::where('nombre_rol', $nombre)->firstOrFail()->id_rol;
    }

    private function totalAdministradores(): int
    {
        return Rol::where('nombre_rol', Rol::ADMINISTRADOR)->firstOrFail()->usuarios()->count();
    }
}
