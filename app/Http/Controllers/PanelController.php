<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PanelController extends Controller
{
    /** Resumen: lo pendiente en la parroquia (Administrador y Editor). */
    public function resumen(Request $request): View
    {
        $usuario = $request->user();
        $pendientes = [];

        $citas = DB::table('citas')->where('estado', 'pendiente')->count();
        $pendientes[] = [
            'numero' => $citas,
            'texto' => $usuario->esAdmin()
                ? ($citas === 1 ? 'cita por aprobar' : 'citas por aprobar')
                : ($citas === 1 ? 'cita pendiente' : 'citas pendientes'),
            'ruta' => 'panel.citas',
            'enlace' => $usuario->esAdmin() ? 'Revisar citas' : 'Ver citas',
        ];

        $intenciones = DB::table('intenciones')->where('estado', 'pendiente')->count();
        $pendientes[] = [
            'numero' => $intenciones,
            'texto' => $intenciones === 1 ? 'intención por revisar' : 'intenciones por revisar',
            'ruta' => 'panel.intenciones',
            'enlace' => 'Revisar intenciones',
        ];

        if ($usuario->esAdmin()) {
            $mensajes = DB::table('contacto')->where('estado', 'pendiente')->count();
            $pendientes[] = [
                'numero' => $mensajes,
                'texto' => $mensajes === 1 ? 'mensaje sin atender' : 'mensajes sin atender',
                'ruta' => 'panel.mensajes',
                'enlace' => 'Ver mensajes',
            ];
        }

        $eventos = DB::table('evento')
            ->where('estado', 'publicado')
            ->whereYear('fecha_inicio', now()->year)
            ->whereMonth('fecha_inicio', now()->month)
            ->count();
        $pendientes[] = [
            'numero' => $eventos,
            'texto' => $eventos === 1 ? 'evento publicado este mes' : 'eventos publicados este mes',
            'ruta' => 'panel.eventos',
            'enlace' => 'Ver eventos',
        ];

        return view('panel.resumen', [
            'saludo' => strtok($usuario->name, ' '),
            'pendientes' => $pendientes,
        ]);
    }
}
