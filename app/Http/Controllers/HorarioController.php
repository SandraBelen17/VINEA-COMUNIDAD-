<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Módulo Horarios.
 * - publico(): página /horarios con la semana en fichas (cualquier visitante).
 * - El resto: panel /panel/horarios (Administrador y Editor/sacristán).
 */
class HorarioController extends Controller
{
    // ---------- Sitio público ----------

    public function publico(): View
    {
        return view('publico.horarios', [
            'semana' => Horario::semana(), // también la usa Inicio
            'hoy' => Horario::diaDeHoy(),
            'capillas' => config('parroquia.capillas'),
        ]);
    }

    // ---------- Panel ----------

    /** Lista de horarios con filtro por tipo y el formulario "Nuevo horario". */
    public function index(Request $request): View
    {
        return $this->pantallaPanel($request, null);
    }

    /** La misma pantalla, pero con el formulario lleno para editar. */
    public function editar(Request $request, Horario $horario): View
    {
        return $this->pantallaPanel($request, $horario);
    }

    public function guardar(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);

        $idParroquia = DB::table('parroquia')->value('id_parroquia');
        if (! $idParroquia) {
            return back()->withInput()
                ->withErrors(['dia' => 'Primero registra los datos de la parroquia.']);
        }

        Horario::create($datos + ['id_parroquia' => $idParroquia]);

        return redirect()->route('panel.horarios', ['tipo' => $datos['tipo']])
            ->with('exito', 'El horario quedó guardado.');
    }

    public function actualizar(Request $request, Horario $horario): RedirectResponse
    {
        $horario->update($this->validar($request));

        return redirect()->route('panel.horarios', ['tipo' => $horario->tipo])
            ->with('exito', 'El horario quedó actualizado.');
    }

    public function eliminar(Horario $horario): RedirectResponse
    {
        $tipo = $horario->tipo;
        $horario->delete();

        return redirect()->route('panel.horarios', ['tipo' => $tipo])
            ->with('exito', 'El horario se eliminó.');
    }

    // ---------- Ayudantes ----------

    private function pantallaPanel(Request $request, ?Horario $editando): View
    {
        $tipo = $request->query('tipo');
        if (! is_string($tipo) || ! array_key_exists($tipo, Horario::TIPOS)) {
            $tipo = null; // "Todos"
        }

        $horarios = Horario::ordenados()
            ->when($tipo, fn ($consulta) => $consulta->where('tipo', $tipo))
            ->get();

        return view('panel.horarios.index', [
            'horarios' => $horarios,
            'filtro' => $tipo,
            'editando' => $editando,
        ]);
    }

    /** Reglas del formulario. Las horas llegan como "19:00" desde <input type="time">. */
    private function validar(Request $request): array
    {
        $datos = $request->validate([
            'tipo' => ['required', Rule::in(array_keys(Horario::TIPOS))],
            'dia' => ['required', Rule::in(array_keys(Horario::DIAS))],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
        ], [
            'tipo.required' => 'Elige el tipo.',
            'tipo.in' => 'Elige un tipo de la lista.',
            'dia.required' => 'Elige el día.',
            'dia.in' => 'Elige un día de la lista.',
            'hora_inicio.required' => 'Escribe la hora de inicio.',
            'hora_inicio.date_format' => 'Escribe una hora válida.',
            'hora_fin.required' => 'Escribe la hora de fin.',
            'hora_fin.date_format' => 'Escribe una hora válida.',
            'hora_fin.after' => 'La hora de fin debe ser después de la hora de inicio.',
        ]);

        // MySQL guarda TIME como "19:00:00"
        $datos['hora_inicio'] .= ':00';
        $datos['hora_fin'] .= ':00';

        return $datos;
    }
}
