<?php

/*
|--------------------------------------------------------------------------
| Textos fijos de la parroquia
|--------------------------------------------------------------------------
| Datos que no están en la base de datos. Se muestran en Inicio y en /horarios.
| [Texto entre corchetes] = dato pendiente; cámbialo aquí cuando se confirme.
*/

return [

    // Capillas: la tabla horarios no guarda el lugar, por eso van aquí.
    // 'zona' => null se muestra como [Zona por confirmar].
    'capillas' => [
        ['nombre' => 'Señor de la Misericordia',  'zona' => null,            'dia' => 'Sábado',  'hora' => '6:00 pm'],
        ['nombre' => 'Lomas de la Soledad',       'zona' => null,            'dia' => 'Domingo', 'hora' => '9:00 am'],
        ['nombre' => 'San Francisco',             'zona' => null,            'dia' => 'Domingo', 'hora' => '10:00 am'],
        ['nombre' => 'Santa Cruz',                'zona' => null,            'dia' => 'Domingo', 'hora' => '11:40 am'],
        ['nombre' => 'San Judas Tadeo',           'zona' => null,            'dia' => 'Domingo', 'hora' => '5:00 pm'],
        ['nombre' => 'Virgen de Juquila',         'zona' => 'Observatorio',  'dia' => 'Domingo', 'hora' => '6:00 pm'],
        ['nombre' => 'San Juan Bautista',         'zona' => 'Loma Bonita',   'dia' => 'Domingo', 'hora' => '7:00 pm'],
        ['nombre' => 'Ermita María de Guadalupe', 'zona' => null,            'dia' => 'Solo en fechas especiales', 'hora' => null],
    ],

    // Sacramentos de Inicio: nombre => lista de requisitos.
    'sacramentos' => [
        'Bautismo' => ['[Requisitos por confirmar]'],
        'Primera Comunión' => ['[Requisitos por confirmar]'],
        'Confirmación' => ['[Requisitos por confirmar]'],
        'Matrimonio' => ['[Requisitos por confirmar]'],
        'Unción de los enfermos' => ['[Requisitos por confirmar]'],
        'Confesión' => ['[Requisitos por confirmar]'],
    ],

];
