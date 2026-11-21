
USE `VineaWeb_base`;

-- Insertar datos en la tabla rol

INSERT INTO `rol` (`id_rol`, `nombre_rol`, `permisos`, `funciones`)
VALUES
(1, 'Administrador', 'Todos los permisos', 'Administrar el sistema'),
(2, 'Editor', 'Gestion de contenido', 'Administrar eventos y grupos'),
(3, 'Cliente', 'Acceso publico', 'Consultar informacion y solicitar servicios');


-- Insertar datos en la tabla users

INSERT INTO `users` (`id`, `name`, `email`, `password`)
VALUES
(1, 'Administrador', 'admin@vinea.com', '$2y$12$rh1O3cwlemmgdSIISO4TReM2b1PHuhwYAT8eBE4LhvxAzbIKd1kea'),
(2, 'Editor', 'editor@vinea.com', '$2y$12$NK.X31AWKlNS8VEzEVcP5eVj8lQJdNWkL5a1MLMIDGNP4fAG3RHeS'),
(3, 'Cliente', 'cliente@vinea.com', '$2y$12$Qv6ibrRqr5Y/LVGE1MBHM.AmdLfFr6KnHHwE/sQqyx/PHfwWT9UW2');


-- Insertar datos en la tabla rol_usuarios

INSERT INTO `rol_usuarios` (`user_id`, `rol_id`)
VALUES
(1, 1),
(2, 2),
(3, 3);


-- Insertar datos en la tabla parroquia

INSERT INTO `parroquia`
(`id_parroquia`, `nombre`, `descripcion`, `correo`, `telefono`, `direccion`)
VALUES
(1, 'Parroquia María Madre de Dios',
 'Parroquia mariana de la Diócesis de Tehuacán, Puebla. Párroco: Pbro. Juan José Herrera Martínez.',
 'contacto@vinea.com', '2381392082',
 'Calle 9 Nte 2601, Santiago Tula, 75720 Tehuacán, Pue.');


-- Insertar datos en la tabla citas

INSERT INTO `citas`
(`id_cita`, `user_id`, `fecha`, `hora`, `tipo_cita`, `motivo`, `estado`)
VALUES
(1, 3, '2026-12-01', '10:00:00', 'Orientacion',
 'Solicitud de orientacion parroquial', 'pendiente'),

(2, 3, '2026-12-03', '12:00:00', 'Informacion',
 'Consulta de informacion', 'aprobada');


-- Insertar datos en la tabla evento

INSERT INTO `evento`
(`id_evento`, `user_id`, `id_parroquia`, `titulo`, `descripcion`,
 `fecha_inicio`, `fecha_fin`, `hora_inicio`, `hora_fin`, `lugar`, `estado`)
VALUES
(1, 2, 1, 'Misa comunitaria', 'Celebracion para la comunidad',
 '2026-12-08', '2026-12-08', '09:00:00', '10:00:00',
 'Templo parroquial', 'publicado'),

(2, 2, 1, 'Reunion de grupos', 'Reunion de organizacion',
 '2026-12-10', '2026-12-10', '17:00:00', '18:00:00',
 'Salon parroquial', 'borrador');


-- Insertar datos en la tabla intenciones

INSERT INTO `intenciones`
(`id_intenciones`, `user_id`, `contenido`, `tipo_intencion`, `estado`, `fecha`)
VALUES
(1, 3, 'Por el bienestar de mi familia',
 'oracion', 'publicada', '2026-11-20'),

(2, 3, 'Una velita por la salud de mis seres queridos',
 'velita', 'pendiente', '2026-11-20');


-- Insertar datos en la tabla contacto

INSERT INTO `contacto`
(`id_contacto`, `nombre`, `correo`, `telefono`, `asunto`, `mensaje`, `estado`)
VALUES
(1, 'Maria Lopez', 'maria@example.com', '2381234567',
 'Informacion', 'Me gustaria recibir informacion de la parroquia',
 'pendiente');


-- Insertar datos en la tabla grupos

INSERT INTO `grupos`
(`id_grupos`, `id_parroquia`, `nombre`, `descripcion`, `horario`, `lugar`, `estado`)
VALUES
(1, 1, 'Grupo juvenil', 'Grupo de participacion juvenil',
 'Sabados 16:00 horas', 'Salon parroquial', 'activo'),

(2, 1, 'Grupo de oracion', 'Grupo dedicado a la oracion comunitaria',
 'Miercoles 17:00 horas', 'Templo parroquial', 'activo');


-- Insertar datos en la tabla horarios

INSERT INTO `horarios`
(`id_horario`, `id_parroquia`, `dia`, `hora_inicio`, `hora_fin`, `tipo`)
VALUES
(1, 1, 'domingo', '09:00:00', '10:00:00', 'misa'),
(2, 1, 'miercoles', '17:00:00', '18:00:00', 'confesion'),
(3, 1, 'lunes', '09:00:00', '14:00:00', 'oficina');


-- Mostrar los datos insertados

SELECT * FROM `users`;
SELECT * FROM `rol`;
SELECT * FROM `rol_usuarios`;
SELECT * FROM `parroquia`;
SELECT * FROM `citas`;
SELECT * FROM `evento`;
SELECT * FROM `intenciones`;
SELECT * FROM `contacto`;
SELECT * FROM `grupos`;
SELECT * FROM `horarios`;
