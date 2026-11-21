-- Versión para InfinityFree: importar en phpMyAdmin dentro de la base que ya da el hosting.
-- Esta versión no crea la base de datos ni la selecciona.

-- crear la tabla users

CREATE TABLE `users` (
        `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(150) NOT NULL,
        `email` VARCHAR(150) NOT NULL UNIQUE,
        `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
        `password` VARCHAR(255) NOT NULL,
        `remember_token` VARCHAR(100) NULL,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL,
        
        PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- crear la tabla rol

CREATE TABLE `rol` (
    `id_rol` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nombre_rol` VARCHAR(50) NOT NULL UNIQUE,
    `permisos` VARCHAR(255) NULL,
    `funciones` VARCHAR(255) NULL,

    PRIMARY KEY (`id_rol`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- crear la tabla rol_usuario

CREATE TABLE `rol_usuarios` (
    `user_id` BIGINT UNSIGNED NOT NULL,
    `rol_id` BIGINT UNSIGNED NOT NULL,

    PRIMARY KEY (`user_id`, `rol_id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- crear la tabla para informacion de la parroquia

CREATE TABLE `parroquia` (
    `id_parroquia` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(150) NOT NULL,
    `descripcion` TEXT NULL,
    `correo` VARCHAR(150) NULL,
    `telefono` VARCHAR(20) NULL,
    `direccion` VARCHAR(255) NULL,
    `logo` VARCHAR(255) NULL,
    `fecha_actualizacion` TIMESTAMP NULL DEFAULT NULL,
    
    PRIMARY KEY (`id_parroquia`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- crear la tabla citas

CREATE TABLE `citas` (
    `id_cita` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `fecha` DATE NOT NULL,
    `hora` TIME NOT NULL,
    `tipo_cita` VARCHAR(100) NOT NULL,
    `motivo` TEXT NOT NULL,
    `estado` ENUM('pendiente', 'aprobada', 'rechazada')
        NOT NULL DEFAULT 'pendiente',
   
    PRIMARY KEY (`id_cita`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- crear la tabla evento

CREATE TABLE `evento` (
    `id_evento` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `id_parroquia` INT UNSIGNED NOT NULL,
    `titulo` VARCHAR(150) NOT NULL,
    `descripcion` TEXT NULL,
    `fecha_inicio` DATE NOT NULL,
    `fecha_fin` DATE NULL,
    `hora_inicio` TIME NULL,
    `hora_fin` TIME NULL,
    `lugar` VARCHAR(255) NULL,
    `estado` ENUM('publicado', 'borrador', 'cancelado')
        NOT NULL DEFAULT 'publicado',
    
    PRIMARY KEY (`id_evento`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- crear la tabla intenciones

CREATE TABLE `intenciones` (
    `id_intenciones` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    `contenido` TEXT NOT NULL,
    `tipo_intencion` ENUM('oracion', 'velita') NOT NULL,
    `estado` ENUM('pendiente', 'publicada', 'rechazada')
        NOT NULL DEFAULT 'pendiente',
    `fecha` DATE NULL,
   
    PRIMARY KEY (`id_intenciones`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- crear la tabla contacto

CREATE TABLE `contacto` (
    `id_contacto` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(150) NOT NULL,
    `correo` VARCHAR(150) NOT NULL,
    `telefono` VARCHAR(20) NULL,
    `asunto` VARCHAR(150) NOT NULL,
    `mensaje` TEXT NOT NULL,
    `estado` ENUM('pendiente', 'atendido') NOT NULL DEFAULT 'pendiente',
    `fecha_envio` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id_contacto`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- crear tabla grupos

CREATE TABLE `grupos` (
    `id_grupos` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_parroquia` INT UNSIGNED NOT NULL,
    `nombre` VARCHAR(150) NOT NULL,
    `descripcion` TEXT NULL,
    `horario` VARCHAR(150) NULL,
    `lugar` VARCHAR(255) NULL,
    `estado` ENUM('activo', 'inactivo')
        NOT NULL DEFAULT 'activo',
    `fecha_registro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id_grupos`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- crear la tabla horarios

CREATE TABLE `horarios` (
    `id_horario` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_parroquia` INT UNSIGNED NOT NULL,
    `dia` ENUM('lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo') NOT NULL,
    `hora_inicio` TIME NOT NULL,
    `hora_fin` TIME NOT NULL,
    `tipo` ENUM('misa', 'confesion', 'oficina') NOT NULL,

    PRIMARY KEY (`id_horario`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- mostrar tablas

SHOW TABLES;

-- FK user_id de rol_usuarios

ALTER TABLE `rol_usuarios`
ADD CONSTRAINT `fk_rol_usuarios_users`
FOREIGN KEY (`user_id`)
REFERENCES `users`(`id`)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- FK rol_id de rol_usuarios

ALTER TABLE `rol_usuarios`
ADD CONSTRAINT `fk_rol_usuarios_rol`
FOREIGN KEY (`rol_id`)
REFERENCES `rol`(`id_rol`)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- Crear FK de la tabla citas

ALTER TABLE `citas`
ADD CONSTRAINT `fk_citas_users`
FOREIGN KEY (`user_id`)
REFERENCES `users`(`id`)
ON UPDATE CASCADE;

-- Crear FK's de la tabla evento

-- FK user_id

ALTER TABLE `evento`
ADD CONSTRAINT `fk_evento_users`
FOREIGN KEY (`user_id`)
REFERENCES `users`(`id`)
ON UPDATE CASCADE;

-- FK id_parroquia

ALTER TABLE `evento`
ADD CONSTRAINT `fk_evento_parroquia`
FOREIGN KEY (`id_parroquia`)
REFERENCES `parroquia`(`id_parroquia`)
ON UPDATE CASCADE;

-- Crear FK de la tabla intenciones

ALTER TABLE `intenciones`
ADD CONSTRAINT `fk_intenciones_users`
FOREIGN KEY (`user_id`)
REFERENCES `users`(`id`)
ON DELETE SET NULL
ON UPDATE CASCADE;

-- Crear FK de la tabla grupos

ALTER TABLE `grupos`
ADD CONSTRAINT `fk_grupos_parroquia`
FOREIGN KEY (`id_parroquia`)
REFERENCES `parroquia`(`id_parroquia`)
ON UPDATE CASCADE;

-- Crear FK de la tabla horarios

ALTER TABLE `horarios`
ADD CONSTRAINT `fk_horarios_parroquia`
FOREIGN KEY (`id_parroquia`)
REFERENCES `parroquia`(`id_parroquia`)
ON UPDATE CASCADE;



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



-- 1. Mostrar todas las tablas

SHOW TABLES;


-- 2. Consultar todos los usuarios

SELECT * FROM `users`;


-- 3. Consultar los roles registrados

SELECT * FROM `rol`;


-- 4. Consultar los usuarios con sus roles

SELECT
    users.id,
    users.name,
    users.email,
    rol.nombre_rol
FROM `users`
INNER JOIN `rol_usuarios`
    ON users.id = rol_usuarios.user_id
INNER JOIN `rol`
    ON rol_usuarios.rol_id = rol.id_rol;


-- 5. Consultar la informacion de la parroquia

SELECT * FROM `parroquia`;


-- 6. Consultar las citas registradas

SELECT
    citas.id_cita,
    users.name AS usuario,
    citas.fecha,
    citas.hora,
    citas.tipo_cita,
    citas.motivo,
    citas.estado
FROM `citas`
INNER JOIN `users`
    ON citas.user_id = users.id;


-- 7. Consultar los eventos con su parroquia

SELECT
    evento.id_evento,
    users.name AS usuario,
    evento.titulo,
    evento.descripcion,
    evento.fecha_inicio,
    evento.fecha_fin,
    evento.hora_inicio,
    evento.hora_fin,
    evento.lugar,
    evento.estado,
    parroquia.nombre AS parroquia
FROM `evento`
INNER JOIN `users`
    ON evento.user_id = users.id
INNER JOIN `parroquia`
    ON evento.id_parroquia = parroquia.id_parroquia;


-- 8. Consultar las intenciones

SELECT
    intenciones.id_intenciones,
    users.name AS usuario,
    intenciones.contenido,
    intenciones.tipo_intencion,
    intenciones.estado,
    intenciones.fecha
FROM `intenciones`
LEFT JOIN `users`
    ON intenciones.user_id = users.id;


-- 9. Consultar los mensajes de contacto

SELECT
    contacto.id_contacto,
    contacto.nombre,
    contacto.correo,
    contacto.telefono,
    contacto.asunto,
    contacto.mensaje,
    contacto.estado,
    contacto.fecha_envio
FROM `contacto`;


-- 10. Consultar los grupos registrados con su parroquia

SELECT
    grupos.id_grupos,
    grupos.nombre,
    grupos.descripcion,
    grupos.horario,
    grupos.lugar,
    grupos.estado,
    parroquia.nombre AS parroquia
FROM `grupos`
INNER JOIN `parroquia`
    ON grupos.id_parroquia = parroquia.id_parroquia;


-- 11. Consultar los horarios de la parroquia

SELECT
    horarios.id_horario,
    parroquia.nombre AS parroquia,
    horarios.dia,
    horarios.hora_inicio,
    horarios.hora_fin,
    horarios.tipo
FROM `horarios`
INNER JOIN `parroquia`
    ON horarios.id_parroquia = parroquia.id_parroquia;


-- 12. Consultar las claves foraneas

SELECT
    TABLE_NAME,
    CONSTRAINT_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = DATABASE()
AND REFERENCED_TABLE_NAME IS NOT NULL
ORDER BY TABLE_NAME;


-- 13. Contar los registros de cada tabla

SELECT 'users' AS tabla, COUNT(*) AS total FROM `users`
UNION ALL
SELECT 'rol', COUNT(*) FROM `rol`
UNION ALL
SELECT 'rol_usuarios', COUNT(*) FROM `rol_usuarios`
UNION ALL
SELECT 'parroquia', COUNT(*) FROM `parroquia`
UNION ALL
SELECT 'citas', COUNT(*) FROM `citas`
UNION ALL
SELECT 'evento', COUNT(*) FROM `evento`
UNION ALL
SELECT 'intenciones', COUNT(*) FROM `intenciones`
UNION ALL
SELECT 'contacto', COUNT(*) FROM `contacto`
UNION ALL
SELECT 'grupos', COUNT(*) FROM `grupos`
UNION ALL
SELECT 'horarios', COUNT(*) FROM `horarios`;
