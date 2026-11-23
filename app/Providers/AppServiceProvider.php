<?php

namespace App\Providers;

use App\Models\Parroquia;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pie de página del sitio: nombre, dirección, teléfono y correo salen de la tabla parroquia
        // (se editan en /panel/parroquia). Si no hay registro, $parroquia llega como null.
        View::composer('layouts.publico', function ($view) {
            $view->with('parroquia', Parroquia::actual());
        });

        // Muro de oraciones: máximo 5 intenciones por minuto desde la misma IP (contra spam).
        // Al pasarse, regresa al formulario con un aviso en vez de la página de error 429.
        RateLimiter::for('muro', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function () {
                return back()->withInput()->withErrors([
                    'contenido' => 'Enviaste varias intenciones seguidas. Espera un minuto y vuelve a intentarlo.',
                ]);
            });
        });

        // Contacto: máximo 3 mensajes por minuto desde la misma IP (contra spam), igual que el muro.
        RateLimiter::for('contacto', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip())->response(function () {
                return back()->withInput()->withErrors([
                    'mensaje' => 'Enviaste varios mensajes seguidos. Espera un minuto y vuelve a intentarlo.',
                ]);
            });
        });
    }
}
