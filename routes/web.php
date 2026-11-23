<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\IntencionController;
use App\Http\Controllers\PanelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas de Vinea Comunidad
|--------------------------------------------------------------------------
| Roles: Administrador (párroco y equipo), Editor (sacristán), Cliente (feligrés).
| El middleware 'rol' (app/Http/Middleware/CheckRole.php) revisa el rol.
| Las páginas marcadas "en construcción" se reemplazan módulo por módulo.
*/

// ---------- Sitio público ----------
Route::view('/', 'publico.inicio')->name('inicio');
Route::get('/horarios', [HorarioController::class, 'publico'])->name('horarios');
Route::get('/calendario', [EventoController::class, 'publico'])->name('calendario');
Route::get('/grupos', [GrupoController::class, 'publico'])->name('grupos');
Route::get('/muro', [IntencionController::class, 'publico'])->name('muro');
Route::post('/muro', [IntencionController::class, 'guardar'])
    ->middleware('throttle:muro') // máx. 5 por minuto (RateLimiter "muro" en AppServiceProvider)
    ->name('muro.guardar');
Route::view('/contacto', 'publico.construccion', ['titulo' => 'Contacto'])->name('contacto');

// ---------- Cuenta (solo visitantes sin sesión) ----------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1'); // máx. 10 intentos por minuto
    Route::get('/registro', [AuthController::class, 'mostrarRegistro'])->name('registro');
    Route::post('/registro', [AuthController::class, 'registro']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ---------- Cliente (feligrés) ----------
Route::middleware(['auth', 'rol:Cliente'])->group(function () {
    Route::view('/mis-citas', 'panel.construccion', ['titulo' => 'Mis citas'])->name('mis-citas');
});

// ---------- Panel: Administrador y Editor ----------
Route::middleware(['auth', 'rol:Administrador,Editor'])->prefix('panel')->name('panel.')->group(function () {
    Route::get('/', [PanelController::class, 'resumen'])->name('resumen');
    // Eventos (aparecen en /calendario cuando están publicados)
    Route::get('/eventos', [EventoController::class, 'index'])->name('eventos');
    Route::post('/eventos', [EventoController::class, 'guardar'])->name('eventos.guardar');
    Route::get('/eventos/{evento}/editar', [EventoController::class, 'editar'])->name('eventos.editar');
    Route::put('/eventos/{evento}', [EventoController::class, 'actualizar'])->name('eventos.actualizar');
    Route::delete('/eventos/{evento}', [EventoController::class, 'eliminar'])->name('eventos.eliminar');
    // Grupos (solo los activos aparecen en /grupos)
    Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos');
    Route::post('/grupos', [GrupoController::class, 'guardar'])->name('grupos.guardar');
    Route::get('/grupos/{grupo}/editar', [GrupoController::class, 'editar'])->name('grupos.editar');
    Route::put('/grupos/{grupo}', [GrupoController::class, 'actualizar'])->name('grupos.actualizar');
    Route::delete('/grupos/{grupo}', [GrupoController::class, 'eliminar'])->name('grupos.eliminar');
    // Horarios (misa, confesión y oficina)
    Route::get('/horarios', [HorarioController::class, 'index'])->name('horarios');
    Route::post('/horarios', [HorarioController::class, 'guardar'])->name('horarios.guardar');
    Route::get('/horarios/{horario}/editar', [HorarioController::class, 'editar'])->name('horarios.editar');
    Route::put('/horarios/{horario}', [HorarioController::class, 'actualizar'])->name('horarios.actualizar');
    Route::delete('/horarios/{horario}', [HorarioController::class, 'eliminar'])->name('horarios.eliminar');
    // Intenciones del muro (moderación: publicar o rechazar)
    Route::get('/intenciones', [IntencionController::class, 'index'])->name('intenciones');
    Route::patch('/intenciones/{intencion}/publicar', [IntencionController::class, 'publicar'])->name('intenciones.publicar');
    Route::patch('/intenciones/{intencion}/rechazar', [IntencionController::class, 'rechazar'])->name('intenciones.rechazar');
    Route::delete('/intenciones/{intencion}', [IntencionController::class, 'eliminar'])->name('intenciones.eliminar');
    // El Editor ve las citas pero no puede aprobarlas ni rechazarlas (se controla en el módulo Citas).
    Route::view('/citas', 'panel.construccion', ['titulo' => 'Citas'])->name('citas');
});

// ---------- Panel: solo Administrador ----------
Route::middleware(['auth', 'rol:Administrador'])->prefix('panel')->name('panel.')->group(function () {
    Route::view('/mensajes', 'panel.construccion', ['titulo' => 'Mensajes'])->name('mensajes');
    Route::view('/usuarios', 'panel.construccion', ['titulo' => 'Usuarios'])->name('usuarios');
    Route::view('/parroquia', 'panel.construccion', ['titulo' => 'Datos de la parroquia'])->name('parroquia');
});
