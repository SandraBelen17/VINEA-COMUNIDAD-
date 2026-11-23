<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'rol' => CheckRole::class,
        ]);

        // El rol se revisa ANTES de buscar el registro de la URL (route model binding).
        // Si no, alguien sin permiso recibía 404 en ids que no existen y 403 en los que sí,
        // y así podía averiguar qué mensajes, usuarios o citas existen.
        $middleware->prependToPriorityList(
            before: \Illuminate\Routing\Middleware\SubstituteBindings::class,
            prepend: CheckRole::class,
        );

        // Si alguien con sesión abre /login o /registro, lo mandamos a su página.
        $middleware->redirectUsersTo(fn ($request) => $request->user()->rutaInicio());
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Error 419 (formulario caducado): pasa si se envía un formulario abierto
        // antes de cerrar sesión o después de mucho tiempo. En vez de la pantalla
        // de error, regresamos a la página anterior con un aviso.
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() === 419) {
                return redirect()->to(url()->previous() ?: route('login'))
                    ->withInput($request->except('_token', 'password', 'password_confirmation'))
                    ->with('info', 'Tu sesión expiró, vuelve a intentarlo.');
            }
        });
    })->create();
