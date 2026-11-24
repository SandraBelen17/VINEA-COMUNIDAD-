<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Módulo Grupos.
 * - publico(): página /grupos con los grupos activos (cualquier visitante).
 * - El resto: panel /panel/grupos (Administrador y Editor/sacristán).
 */
class GrupoController extends Controller
{
    // ---------- Sitio público ----------

    public function publico(): View
    {
        return view('publico.grupos', [
            'grupos' => Grupo::activos()->get(),
        ]);
    }

    // ---------- Panel ----------

    /** Lista de grupos y el formulario "Nuevo grupo". */
    public function index(): View
    {
        return $this->pantallaPanel(null);
    }

    /** La misma pantalla, pero con el formulario lleno para editar. */
    public function editar(Grupo $grupo): View
    {
        return $this->pantallaPanel($grupo);
    }

    public function guardar(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);

        $idParroquia = DB::table('parroquia')->value('id_parroquia');
        if (! $idParroquia) {
            return back()->withInput()
                ->withErrors(['nombre' => 'Primero registra los datos de la parroquia.']);
        }

        Grupo::create($datos + ['id_parroquia' => $idParroquia]);

        return redirect()->route('panel.grupos')->with('exito', 'Grupo guardado.');
    }

    public function actualizar(Request $request, Grupo $grupo): RedirectResponse
    {
        $grupo->update($this->validar($request));

        return redirect()->route('panel.grupos')->with('exito', 'Grupo actualizado.');
    }

    public function eliminar(Grupo $grupo): RedirectResponse
    {
        $grupo->delete();

        return redirect()->route('panel.grupos')->with('exito', 'El grupo se eliminó.');
    }

    // ---------- Ayudantes ----------

    private function pantallaPanel(?Grupo $editando): View
    {
        return view('panel.grupos.index', [
            // Primero los activos, luego los inactivos; cada parte por nombre
            'grupos' => Grupo::orderByRaw("FIELD(estado, 'activo', 'inactivo')")->orderBy('nombre')->get(),
            'editando' => $editando,
        ]);
    }

    private function validar(Request $request): array
    {
        // WhatsApp: se quitan espacios, guiones y paréntesis; si lo escriben con +52 o 52, se quita
        $numero = preg_replace('/\D/', '', (string) $request->input('whatsapp'));
        if (strlen($numero) === 12 && str_starts_with($numero, '52')) {
            $numero = substr($numero, 2);
        }
        $request->merge(['whatsapp' => $numero === '' ? null : $numero]);

        return $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'horario' => ['nullable', 'string', 'max:150'],
            'lugar' => ['nullable', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'coordinador' => ['nullable', 'string', 'max:150'],
            'whatsapp' => ['nullable', 'digits:10'],
            'estado' => ['required', Rule::in(array_keys(Grupo::ESTADOS))],
        ], [
            'nombre.required' => 'Escribe el nombre del grupo.',
            'nombre.max' => 'El nombre puede tener hasta 150 caracteres.',
            'horario.max' => 'El horario puede tener hasta 150 caracteres.',
            'lugar.max' => 'El lugar puede tener hasta 255 caracteres.',
            'descripcion.max' => 'La descripción puede tener hasta 2000 caracteres.',
            'coordinador.max' => 'El nombre del coordinador puede tener hasta 150 caracteres.',
            'whatsapp.digits' => 'El WhatsApp debe tener 10 dígitos, por ejemplo 238 123 4567.',
            'estado.required' => 'Elige el estado.',
            'estado.in' => 'Elige un estado de la lista.',
        ]);
    }
}
