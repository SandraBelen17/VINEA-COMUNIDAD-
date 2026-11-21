
USE `VineaWeb_base`;

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
