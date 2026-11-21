-- Horarios reales de la Parroquia María Madre de Dios (módulo Horarios)
-- Reemplaza los 3 horarios de prueba por los reales.
-- Úsalo en phpMyAdmin con la base ya seleccionada (local: VineaWeb_base; InfinityFree: la del hosting).
-- Requiere que exista la parroquia con id_parroquia = 1 (04-datos-de-prueba.sql).
-- Las misas de las capillas NO van aquí: están como texto fijo en la vista (la tabla no guarda el lugar).

-- Borrar los horarios que hay y reiniciar el contador

DELETE FROM `horarios`;

ALTER TABLE `horarios` AUTO_INCREMENT = 1;

-- Misas en el templo parroquial (1 hora cada una)

INSERT INTO `horarios` (`id_parroquia`, `dia`, `hora_inicio`, `hora_fin`, `tipo`)
VALUES
(1, 'lunes',     '19:00:00', '20:00:00', 'misa'),
(1, 'martes',    '19:00:00', '20:00:00', 'misa'),
(1, 'miercoles', '19:00:00', '20:00:00', 'misa'),
(1, 'jueves',    '19:00:00', '20:00:00', 'misa'),
(1, 'viernes',   '19:00:00', '20:00:00', 'misa'),
(1, 'sabado',    '19:00:00', '20:00:00', 'misa'),
(1, 'domingo',   '07:00:00', '08:00:00', 'misa'),
(1, 'domingo',   '13:00:00', '14:00:00', 'misa');

-- Confesiones: jueves durante la hora santa
-- (el horario diario de 8 a 9 pm está más abajo)

INSERT INTO `horarios` (`id_parroquia`, `dia`, `hora_inicio`, `hora_fin`, `tipo`)
VALUES
(1, 'jueves', '18:00:00', '19:00:00', 'confesion');

-- Oficina parroquial: martes a domingo, 3 a 7 pm
-- (falta el horario del domingo por la mañana; se agrega desde el panel cuando se confirme)

INSERT INTO `horarios` (`id_parroquia`, `dia`, `hora_inicio`, `hora_fin`, `tipo`)
VALUES
(1, 'martes',    '15:00:00', '19:00:00', 'oficina'),
(1, 'miercoles', '15:00:00', '19:00:00', 'oficina'),
(1, 'jueves',    '15:00:00', '19:00:00', 'oficina'),
(1, 'viernes',   '15:00:00', '19:00:00', 'oficina'),
(1, 'sabado',    '15:00:00', '19:00:00', 'oficina'),
(1, 'domingo',   '15:00:00', '19:00:00', 'oficina');

-- Confesiones: todos los días de 8 a 9 pm

INSERT INTO `horarios` (`id_parroquia`, `dia`, `hora_inicio`, `hora_fin`, `tipo`)
VALUES
(1, 'lunes',     '20:00:00', '21:00:00', 'confesion'),
(1, 'martes',    '20:00:00', '21:00:00', 'confesion'),
(1, 'miercoles', '20:00:00', '21:00:00', 'confesion'),
(1, 'jueves',    '20:00:00', '21:00:00', 'confesion'),
(1, 'viernes',   '20:00:00', '21:00:00', 'confesion'),
(1, 'sabado',    '20:00:00', '21:00:00', 'confesion'),
(1, 'domingo',   '20:00:00', '21:00:00', 'confesion');

-- Verificar (deben salir 22 filas)

SELECT `id_horario`, `dia`, `hora_inicio`, `hora_fin`, `tipo`
FROM `horarios`
ORDER BY FIELD(`dia`, 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'), `hora_inicio`;
