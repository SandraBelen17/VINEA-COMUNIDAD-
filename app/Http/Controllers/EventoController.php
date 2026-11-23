<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Módulo Eventos / Calendario.
 * - publico(): página /calendario con los eventos publicados del mes (cualquier visitante).
 * - El resto: panel /panel/eventos (Administrador y Editor/sacristán).
 */
class EventoController extends Controller
{
    // ---------- Sitio público ----------

    /** /calendario?mes=2026-12 (sin "mes" = el mes actual) */
    public function publico(Request $request): View
    {
        $mes = $this->mesPedido($request->query('mes'));

        return view('publico.calendario', [
            'eventos' => Evento::publicadosDelMes($mes)->get(),
            'mes' => $mes,
            'titulo' => Evento::nombreMes($mes),
            'anterior' => $mes->copy()->subMonthNoOverflow()->format('Y-m'),
            'siguiente' => $mes->copy()->addMonthNoOverflow()->format('Y-m'),
        ]);
    }

    // ---------- Panel ----------

    /** Lista de eventos y el formulario "Nuevo evento". */
    public function index(): View
    {
        return $this->pantallaPanel(null);
    }

    /** La misma pantalla, pero con el formulario lleno para editar. */
    public function editar(Evento $evento): View
    {
        return $this->pantallaPanel($evento);
    }

    public function guardar(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);

        $idParroquia = DB::table('parroquia')->value('id_parroquia');
        if (! $idParroquia) {
            return back()->withInput()
                ->withErrors(['titulo' => 'Primero registra los datos de la parroquia.']);
        }

        // user_id = quien lo crea; al editar no cambia
        Evento::create($datos + [
            'user_id' => $request->user()->id,
            'id_parroquia' => $idParroquia,
        ]);

        return redirect()->route('panel.eventos')->with('exito', 'Evento guardado.');
    }

    public function actualizar(Request $request, Evento $evento): RedirectResponse
    {
        $evento->update($this->validar($request));

        return redirect()->route('panel.eventos')->with('exito', 'Evento actualizado.');
    }

    public function eliminar(Evento $evento): RedirectResponse
    {
        $evento->delete();

        return redirect()->route('panel.eventos')->with('exito', 'El evento se eliminó.');
    }

    // ---------- Ayudantes ----------

    private function pantallaPanel(?Evento $editando): View
    {
        return view('panel.eventos.index', [
            // Los más recientes arriba
            'eventos' => Evento::orderByDesc('fecha_inicio')->orderByDesc('hora_inicio')->get(),
            'editando' => $editando,
        ]);
    }

    /** "2026-12" → primer día de ese mes. Si viene vacío o mal escrito, el mes actual. */
    private function mesPedido(mixed $texto): Carbon
    {
        if (is_string($texto) && preg_match('/^(\d{4})-(0[1-9]|1[0-2])$/', $texto, $partes)) {
            $anio = (int) $partes[1];
            if ($anio >= 2000 && $anio <= 2100) {
                return Carbon::create($anio, (int) $partes[2], 1);
            }
        }

        return now()->startOfMonth();
    }

    /** Reglas del formulario. Las horas llegan como "19:00" desde <input type="time">. */
    private function validar(Request $request): array
    {
        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:150'],
            'lugar' => ['nullable', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'fecha_inicio' => ['required', 'date_format:Y-m-d'],
            'fecha_fin' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:fecha_inicio'],
            'hora_inicio' => ['nullable', 'required_with:hora_fin', 'date_format:H:i'],
            'hora_fin' => ['nullable', 'date_format:H:i', 'after:hora_inicio'],
            'estado' => ['required', Rule::in(array_keys(Evento::ESTADOS))],
        ], [
            'titulo.required' => 'Escribe el título.',
            'titulo.max' => 'El título puede tener hasta 150 caracteres.',
            'lugar.max' => 'El lugar puede tener hasta 255 caracteres.',
            'descripcion.max' => 'La descripción puede tener hasta 2000 caracteres.',
            'fecha_inicio.required' => 'Elige la fecha de inicio.',
            'fecha_inicio.date_format' => 'Escribe una fecha válida.',
            'fecha_fin.date_format' => 'Escribe una fecha válida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin no puede ser antes de la fecha de inicio.',
            'hora_inicio.required_with' => 'Si pones hora de fin, escribe también la hora de inicio.',
            'hora_inicio.date_format' => 'Escribe una hora válida.',
            'hora_fin.date_format' => 'Escribe una hora válida.',
            'hora_fin.after' => 'La hora de fin debe ser después de la hora de inicio.',
            'estado.required' => 'Elige el estado.',
            'estado.in' => 'Elige un estado de la lista.',
        ]);

        // MySQL guarda TIME como "19:00:00"
        foreach (['hora_inicio', 'hora_fin'] as $campo) {
            if ($datos[$campo] ?? null) {
                $datos[$campo] .= ':00';
            }
        }

        return $datos;
    }
}
