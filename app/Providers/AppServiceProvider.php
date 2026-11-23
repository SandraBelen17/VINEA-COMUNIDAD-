<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        // Muro de oraciones: máximo 5 intenciones por minuto desde la misma IP (contra spam).
        // Al pasarse, regresa al formulario con un aviso en vez de la página de error 429.
        RateLimiter::for('muro', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function () {
                return back()->withInput()->withErrors([
                    'contenido' => 'Enviaste varias intenciones seguidas. Espera un minuto y vuelve a intentarlo.',
                ]);
            });
        });
    }
}
