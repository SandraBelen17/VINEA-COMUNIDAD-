<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Módulo Citas.
 * - misCitas() y pedir(): /mis-citas del Cliente. Solo ve y crea SUS citas
 *   (todo se filtra con su user_id; no hay rutas de cliente con id de cita).
 * - index(): /panel/citas para Administrador y Editor (el Editor solo lee).
 * - aprobar() y rechazar(): solo Administrador (ruta en el grupo rol:Administrador
 *   y además se revisa aquí).
 */
class CitaController extends Controller
{
    // ---------- Cliente: Mis citas ----------

    public function misCitas(Request $request): View
    {
        return view('panel.mis-citas', [
            'citas' => Cita::where('user_id', $request->user()->id)
                ->orderBy('fecha')
                ->orderBy('hora')
                ->get(),
        ]);
    }

    public function pedir(Request $request): RedirectResponse
    {
        $hoy = now()->toDateString();
        $limite = now()->addYear()->toDateString();

        $datos = $request->validate([
            'fecha' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . $hoy, 'before_or_equal:' . $limite],
            'hora' => ['required', 'date_format:H:i'],
            'tipo_cita' => ['required', Rule::in(array_keys(Cita::TIPOS))],
            'motivo' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'fecha.required' => 'Elige la fecha.',
            'fecha.date_format' => 'Escribe una fecha válida.',
            'fecha.after_or_equal' => 'La fecha no puede ser en el pasado.',
            'fecha.before_or_equal' => 'Solo puedes pedir citas hasta con un año de anticipación.',
            'hora.required' => 'Elige la hora.',
            'hora.date_format' => 'Escribe una hora válida.',
            'tipo_cita.required' => 'Elige el tipo de cita.',
            'tipo_cita.in' => 'Elige un tipo de cita de la lista.',
            'motivo.required' => 'Escribe el motivo de la cita.',
            'motivo.min' => 'Cuéntanos un poco más el motivo.',
            'motivo.max' => 'El motivo puede tener hasta 1000 caracteres.',
        ]);

        // Si es para hoy, la hora tampoco puede haber pasado
        $cuando = Carbon::createFromFormat('Y-m-d H:i', $datos['fecha'] . ' ' . $datos['hora']);
        if ($cuando->lessThanOrEqualTo(now())) {
            throw ValidationException::withMessages([
                'hora' => 'Esa hora ya pasó. Elige una hora más tarde u otro día.',
            ]);
        }

        // user_id y estado los pone el sistema, nunca el formulario
        Cita::create([
            'user_id' => $request->user()->id,
            'fecha' => $datos['fecha'],
            'hora' => $datos['hora'] . ':00', // MySQL guarda TIME como "10:00:00"
            'tipo_cita' => $datos['tipo_cita'],
            'motivo' => $datos['motivo'],
            'estado' => 'pendiente',
        ]);

        return redirect()->route('mis-citas')
            ->with('exito', 'Tu solicitud se envió. Aquí puedes revisar su estado.');
    }

    // ---------- Panel ----------

    /** Pestañas: Pendientes (de entrada), Aprobadas, Rechazadas y Todas. */
    public function index(Request $request): View
    {
        $estado = $request->query('estado');
        if ($estado !== 'todas' && (! is_string($estado) || ! array_key_exists($estado, Cita::ESTADOS))) {
            $estado = 'pendiente';
        }

        $consulta = Cita::with('usuario')->when($estado !== 'todas', fn ($c) => $c->where('estado', $estado));

        // Pendientes: la más próxima primero. Las demás: la más reciente primero.
        $consulta = $estado === 'pendiente'
            ? $consulta->orderBy('fecha')->orderBy('hora')
            : $consulta->orderByDesc('fecha')->orderByDesc('hora');

        return view('panel.citas.index', [
            'citas' => $consulta->simplePaginate(20)->withQueryString(),
            'estado' => $estado,
            'pendientes' => Cita::where('estado', 'pendiente')->count(),
            'puedeDecidir' => $request->user()->esAdmin(),
        ]);
    }

    public function aprobar(Request $request, Cita $cita): RedirectResponse
    {
        return $this->cambiarEstado($request, $cita, 'aprobada', 'La cita quedó aprobada.');
    }

    public function rechazar(Request $request, Cita $cita): RedirectResponse
    {
        return $this->cambiarEstado($request, $cita, 'rechazada', 'La cita quedó rechazada.');
    }

    // ---------- Ayudantes ----------

    private function cambiarEstado(Request $request, Cita $cita, string $estado, string $mensaje): RedirectResponse
    {
        // Segunda protección (la primera es la ruta): solo el Administrador decide
        abort_unless($request->user()->esAdmin(), 403, 'Solo el párroco puede aprobar o rechazar citas.');

        $cita->update(['estado' => $estado]);

        return back()->with('exito', $mensaje);
    }
}
