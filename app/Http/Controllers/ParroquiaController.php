<?php

namespace App\Http\Controllers;

use App\Models\Parroquia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Datos de la parroquia: /panel/parroquia, SOLO Administrador.
 * Edita el único registro de la tabla parroquia. Lo que se guarda aquí sale en el
 * pie de página de todo el sitio y en /contacto. El logo es fijo.
 */
class ParroquiaController extends Controller
{
    public function editar(): View
    {
        return view('panel.parroquia', [
            'parroquia' => Parroquia::actual() ?? new Parroquia(),
        ]);
    }

    public function guardar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'correo' => ['nullable', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[0-9 +()\-]{7,20}$/'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:5000'],
        ], [
            'nombre.required' => 'Escribe el nombre de la parroquia.',
            'nombre.max' => 'El nombre puede tener hasta 150 caracteres.',
            'correo.email' => 'Escribe un correo válido.',
            'correo.max' => 'El correo puede tener hasta 150 caracteres.',
            'telefono.max' => 'El teléfono puede tener hasta 20 caracteres.',
            'telefono.regex' => 'Escribe un teléfono válido (solo números, espacios o guiones).',
            'direccion.max' => 'La dirección puede tener hasta 255 caracteres.',
            'descripcion.max' => 'La descripción puede tener hasta 5000 caracteres.',
        ]);

        // Si todavía no hay registro, se crea; si ya hay, se actualiza (siempre es uno solo)
        $parroquia = Parroquia::actual() ?? new Parroquia();
        $parroquia->fill($datos + ['fecha_actualizacion' => now()])->save();

        return redirect()->route('panel.parroquia')->with('exito', 'Los datos de la parroquia quedaron guardados.');
    }
}
