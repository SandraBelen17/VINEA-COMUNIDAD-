<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Grupo;
use App\Models\Horario;
use Illuminate\View\View;

/**
 * Página de inicio: slider, parroquia, horarios de la semana, capillas, sacramentos,
 * próximos eventos, grupos y la invitación al muro.
 * Los textos fijos (capillas y sacramentos) están en config/parroquia.php.
 */
class InicioController extends Controller
{
    public function index(): View
    {
        return view('publico.inicio', [
            'semana' => Horario::semana(),        // las mismas fichas que /horarios
            'hoy' => Horario::diaDeHoy(),
            'eventos' => Evento::proximos()->limit(3)->get(),
            'grupos' => Grupo::activos()->get(),
            'capillas' => config('parroquia.capillas'),
            'sacramentos' => config('parroquia.sacramentos'),
        ]);
    }
}
