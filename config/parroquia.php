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
        'Bautismo' => [
            'Solicitante: no estar bautizado. En niños pequeños, contar con la aceptación de los padres y la esperanza fundada de educarlos en la fe.',
            'Documentos: acta de nacimiento del bautizado y, en muchos casos, plática o curso prebautismal para padres y padrinos.',
            'Padrinos: ser católicos (idealmente confirmados y con Primera Comunión), tener al menos 16 años cumplidos, llevar una vida congruente con la fe y no ser padre ni madre del bautizado. Si están casados, suele pedirse matrimonio por la Iglesia; si son solteros, sus actas o comprobantes de sacramentos.',
        ],
        'Primera Comunión' => [
            'Preparación: haber recibido el Bautismo, tener uso de razón (habitualmente desde los 9 años o según la diócesis) y cursar la catequesis correspondiente.',
            'Para comulgar: estar bautizado, en estado de gracia (confesado si hay pecado mortal) y guardar el ayuno eucarístico de al menos una hora antes (solo se permiten agua y medicinas, con excepciones para enfermos o mayores).',
            'Documentos: acta o fe de Bautismo.',
        ],
        'Confirmación' => [
            'Preparación: estar bautizado, haber hecho o estar por hacer la Primera Comunión, tener la edad o etapa catequética indicada por la parroquia (a menudo desde los 12 a 14 años) y completar el curso de preparación.',
            'Padrino o madrina: católico confirmado, practicante y en comunión con la Iglesia.',
            'Documentos: fe de Bautismo y confesión previa.',
        ],
        'Reconciliación (Confesión)' => [
            'Requisitos: estar bautizado, tener uso de razón, arrepentimiento sincero (contrición), propósito de enmienda, confesar los pecados y cumplir la penitencia.',
        ],
        'Matrimonio' => [
            'Documentos: fe de Bautismo actualizada (a menudo con vigencia de 3 a 6 meses), actas de nacimiento, comprobantes de Confirmación y/o Primera Comunión, identificación oficial y, según la parroquia o la legislación local, acta de matrimonio civil o datos de padrinos y testigos.',
            'Preparación: pláticas o curso prematrimonial y presentación del expediente matrimonial en la parroquia.',
        ],
        'Unción de los enfermos' => [
            'Ser fiel bautizado que comienza a estar en peligro por enfermedad grave o vejez. Puede recibirse más de una vez si la enfermedad se agrava o reaparece.',
        ],
    ],

];
