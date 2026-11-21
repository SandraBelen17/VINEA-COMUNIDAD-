<?php

/*
|--------------------------------------------------------------------------
| Menú lateral del panel
|--------------------------------------------------------------------------
| Cada opción dice qué roles la ven. El layout panel.blade.php recorre esta
| lista y solo muestra las opciones del rol del usuario.
| Íconos: nombres de Lucide (https://lucide.dev/icons).
*/

$admin = 'Administrador';
$editor = 'Editor';
$cliente = 'Cliente';

return [
    'menu' => [
        ['texto' => 'Resumen',     'ruta' => 'panel.resumen',     'icono' => 'house',         'roles' => [$admin, $editor]],
        ['texto' => 'Eventos',     'ruta' => 'panel.eventos',     'icono' => 'calendar-days', 'roles' => [$admin, $editor]],
        ['texto' => 'Grupos',      'ruta' => 'panel.grupos',      'icono' => 'users',         'roles' => [$admin, $editor]],
        ['texto' => 'Horarios',    'ruta' => 'panel.horarios',    'icono' => 'clock',         'roles' => [$admin, $editor]],
        ['texto' => 'Intenciones', 'ruta' => 'panel.intenciones', 'icono' => 'flame',         'roles' => [$admin, $editor]],
        ['texto' => 'Citas',       'ruta' => 'panel.citas',       'icono' => 'book-open',     'roles' => [$admin, $editor]],
        ['texto' => 'Mensajes',    'ruta' => 'panel.mensajes',    'icono' => 'inbox',         'roles' => [$admin]],
        ['texto' => 'Usuarios',    'ruta' => 'panel.usuarios',    'icono' => 'user',          'roles' => [$admin]],
        ['texto' => 'Parroquia',   'ruta' => 'panel.parroquia',   'icono' => 'settings',      'roles' => [$admin]],

        ['texto' => 'Mis citas',         'ruta' => 'mis-citas', 'icono' => 'book-open', 'roles' => [$cliente]],
        ['texto' => 'Muro de oraciones', 'ruta' => 'muro',      'icono' => 'flame',     'roles' => [$cliente]],
    ],
];
