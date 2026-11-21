<?php

use App\Http\Controllers\AuthController;
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
Route::view('/horarios', 'publico.construccion', ['titulo' => 'Horarios'])->name('horarios');
Route::view('/calendario', 'publico.construccion', ['titulo' => 'Calendario'])->name('calendario');
Route::view('/grupos', 'publico.construccion', ['titulo' => 'Grupos'])->name('grupos');
Route::view('/muro', 'publico.construccion', ['titulo' => 'Muro de oraciones'])->name('muro');
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
    Route::view('/eventos', 'panel.construccion', ['titulo' => 'Eventos'])->name('eventos');
    Route::view('/grupos', 'panel.construccion', ['titulo' => 'Grupos'])->name('grupos');
    Route::view('/horarios', 'panel.construccion', ['titulo' => 'Horarios'])->name('horarios');
    Route::view('/intenciones', 'panel.construccion', ['titulo' => 'Intenciones'])->name('intenciones');
    // El Editor ve las citas pero no puede aprobarlas ni rechazarlas (se controla en el módulo Citas).
    Route::view('/citas', 'panel.construccion', ['titulo' => 'Citas'])->name('citas');
});

// ---------- Panel: solo Administrador ----------
Route::middleware(['auth', 'rol:Administrador'])->prefix('panel')->name('panel.')->group(function () {
    Route::view('/mensajes', 'panel.construccion', ['titulo' => 'Mensajes'])->name('mensajes');
    Route::view('/usuarios', 'panel.construccion', ['titulo' => 'Usuarios'])->name('usuarios');
    Route::view('/parroquia', 'panel.construccion', ['titulo' => 'Datos de la parroquia'])->name('parroquia');
});
