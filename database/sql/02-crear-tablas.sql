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
