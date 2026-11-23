<?php

namespace App\Http\Controllers;

use App\Models\Intencion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Módulo Muro de oraciones + Intenciones.
 * - publico() y guardar(): página /muro. Cualquiera, con o sin cuenta, deja una intención.
 * - El resto: panel /panel/intenciones (Administrador y Editor) para publicar o rechazar.
 */
class IntencionController extends Controller
{
    // ---------- Sitio público ----------

    /** Muro: formulario y las intenciones publicadas, de la más nueva a la más vieja. */
    public function publico(): View
    {
        $intenciones = Intencion::where('estado', 'publicada')
            ->orderByDesc('fecha')
            ->orderByDesc('id_intenciones')
            ->simplePaginate(10)
            ->fragment('comunidad');

        return view('publico.muro', ['intenciones' => $intenciones]);
    }

    /** POST /muro (con límite de envíos por minuto: RateLimiter "muro" en AppServiceProvider). */
    public function guardar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'tipo_intencion' => ['required', Rule::in(array_keys(Intencion::TIPOS))],
            'contenido' => ['required', 'string', 'min:3', 'max:500'],
        ], [
            'tipo_intencion.required' => 'Elige si es una oración o una velita.',
            'tipo_intencion.in' => 'Elige si es una oración o una velita.',
            'contenido.required' => 'Escribe tu intención.',
            'contenido.min' => 'Escribe un poco más tu intención.',
            'contenido.max' => 'Tu intención puede tener hasta 500 caracteres.',
        ]);

        Intencion::create($datos + [
            'user_id' => $request->user()?->id, // sin sesión queda vacío
            'estado' => 'pendiente',
            'fecha' => now()->toDateString(),
        ]);

        return redirect()->to(route('muro') . '#deja-tu-intencion')
            ->with('exito', 'Tu intención se publicará cuando sea revisada.');
    }

    // ---------- Panel ----------

    /** Pestañas: Por revisar (pendiente), Publicadas y Rechazadas. */
    public function index(Request $request): View
    {
        $estado = $request->query('estado');
        if (! is_string($estado) || ! array_key_exists($estado, Intencion::ESTADOS)) {
            $estado = 'pendiente';
        }

        $intenciones = Intencion::with('usuario')
            ->where('estado', $estado)
            ->orderByDesc('fecha')
            ->orderByDesc('id_intenciones')
            ->simplePaginate(20)
            ->withQueryString();

        return view('panel.intenciones.index', [
            'intenciones' => $intenciones,
            'estado' => $estado,
            'porRevisar' => Intencion::where('estado', 'pendiente')->count(),
        ]);
    }

    public function publicar(Intencion $intencion): RedirectResponse
    {
        $intencion->update(['estado' => 'publicada']);

        return back()->with('exito', 'La intención se publicó en el muro.');
    }

    public function rechazar(Intencion $intencion): RedirectResponse
    {
        $intencion->update(['estado' => 'rechazada']);

        return back()->with('exito', 'La intención se rechazó.');
    }

    public function eliminar(Intencion $intencion): RedirectResponse
    {
        $intencion->delete();

        return back()->with('exito', 'La intención se eliminó.');
    }
}
