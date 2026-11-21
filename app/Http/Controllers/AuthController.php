<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Iniciar sesión, crear cuenta y cerrar sesión.
 * Las contraseñas nunca se guardan en texto plano: el modelo User
 * tiene el cast 'password' => 'hashed', que aplica bcrypt al guardar.
 */
class AuthController extends Controller
{
    public function mostrarLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Escribe tu correo electrónico.',
            'email.email' => 'Escribe un correo válido.',
            'password.required' => 'Escribe tu contraseña.',
        ]);

        // Auth::attempt compara la contraseña escrita con el hash guardado en la BD.
        if (! Auth::attempt($datos, $request->boolean('recordarme'))) {
            return back()
                ->withInput($request->only('email', 'recordarme'))
                ->withErrors(['email' => 'El correo o la contraseña no son correctos.']);
        }

        $request->session()->regenerate(); // evita el robo de sesión (session fixation)

        return redirect()->intended($request->user()->rutaInicio());
    }

    public function mostrarRegistro(): View
    {
        return view('auth.registro');
    }

    /** Crea la cuenta de un feligrés: siempre con el rol Cliente. */
    public function registro(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required' => 'Escribe tu nombre completo.',
            'email.required' => 'Escribe tu correo electrónico.',
            'email.email' => 'Escribe un correo válido.',
            'email.unique' => 'Ya existe una cuenta con este correo.',
            'password.required' => 'Escribe una contraseña.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        $rolCliente = Rol::where('nombre_rol', Rol::CLIENTE)->firstOrFail();

        // Si algo falla, no se guarda ni el usuario ni su rol.
        $usuario = DB::transaction(function () use ($datos, $rolCliente) {
            $usuario = User::create($datos); // la contraseña se guarda con hash
            $usuario->roles()->attach($rolCliente->id_rol);

            return $usuario;
        });

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('mis-citas')
            ->with('exito', '¡Bienvenido(a)! Tu cuenta quedó creada.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Cerraste tu sesión.');
    }
}
