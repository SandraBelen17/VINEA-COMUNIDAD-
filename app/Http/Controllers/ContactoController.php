<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use App\Models\Horario;
use App\Models\Parroquia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Módulo Contacto + Mensajes.
 * - publico() y enviar(): página /contacto (cualquier visitante, con o sin cuenta).
 * - El resto: /panel/mensajes, SOLO Administrador (rutas en el grupo rol:Administrador).
 */
class ContactoController extends Controller
{
    // ---------- Sitio público ----------

    /** Formulario y "Visítanos" con los datos de la parroquia y el horario de oficina. */
    public function publico(): View
    {
        $parroquia = Parroquia::actual(); // mismos datos que el pie de página (se editan en /panel/parroquia)

        return view('publico.contacto', [
            'direccion' => $parroquia?->direccion,
            'telefono' => $parroquia?->telefono,
            'telefonoTexto' => $parroquia?->telefono_texto,
            'correo' => $parroquia?->correo,
            'oficina' => Horario::resumen('oficina'),
        ]);
    }

    /** POST /contacto (con límite de envíos por minuto: RateLimiter "contacto" en AppServiceProvider). */
    public function enviar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'correo' => ['required', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[0-9 +()\-]{7,20}$/'],
            'asunto' => ['required', 'string', 'max:150'],
            'mensaje' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'nombre.required' => 'Escribe tu nombre.',
            'nombre.max' => 'El nombre puede tener hasta 150 caracteres.',
            'correo.required' => 'Escribe tu correo para poder responderte.',
            'correo.email' => 'Escribe un correo válido.',
            'correo.max' => 'El correo puede tener hasta 150 caracteres.',
            'telefono.max' => 'El teléfono puede tener hasta 20 caracteres.',
            'telefono.regex' => 'Escribe un teléfono válido (solo números, espacios o guiones).',
            'asunto.required' => 'Escribe el asunto.',
            'asunto.max' => 'El asunto puede tener hasta 150 caracteres.',
            'mensaje.required' => 'Escribe tu mensaje.',
            'mensaje.min' => 'Cuéntanos un poco más en tu mensaje.',
            'mensaje.max' => 'El mensaje puede tener hasta 2000 caracteres.',
        ]);

        // estado y fecha los pone el sistema (la fecha con la hora de México, no la del servidor MySQL)
        Contacto::create($datos + [
            'estado' => 'pendiente',
            'fecha_envio' => now(),
        ]);

        return redirect()->to(route('contacto') . '#escribenos')
            ->with('exito', 'Gracias, te responderemos pronto.');
    }

    // ---------- Panel (solo Administrador) ----------

    /** Pestañas: Todos (de entrada, pendientes primero), Sin atender y Atendidos. */
    public function index(Request $request): View
    {
        $estado = $request->query('estado');
        if (! is_string($estado) || ! array_key_exists($estado, Contacto::ESTADOS)) {
            $estado = 'todos';
        }

        $mensajes = Contacto::when($estado !== 'todos', fn ($c) => $c->where('estado', $estado))
            ->pendientesPrimero()
            ->simplePaginate(20)
            ->withQueryString();

        return view('panel.mensajes.index', [
            'mensajes' => $mensajes,
            'estado' => $estado,
            'sinAtender' => Contacto::where('estado', 'pendiente')->count(),
        ]);
    }

    /** Abrir un mensaje completo. */
    public function ver(Contacto $contacto): View
    {
        return view('panel.mensajes.ver', ['m' => $contacto]);
    }

    public function atender(Contacto $contacto): RedirectResponse
    {
        $contacto->update(['estado' => 'atendido']);

        return back()->with('exito', 'El mensaje quedó como atendido.');
    }

    public function eliminar(Contacto $contacto): RedirectResponse
    {
        $contacto->delete();

        // A la lista (si se eliminó desde el detalle, esa página ya no existe)
        return redirect()->route('panel.mensajes')->with('exito', 'El mensaje se eliminó.');
    }
}
