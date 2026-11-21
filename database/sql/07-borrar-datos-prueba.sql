
USE `VineaWeb_base`;

-- Eliminar los datos de las tablas

DELETE FROM `contacto`;

DELETE FROM `intenciones`;

DELETE FROM `citas`;

DELETE FROM `evento`;

DELETE FROM `grupos`;

DELETE FROM `horarios`;

DELETE FROM `rol_usuarios`;

DELETE FROM `users`;

DELETE FROM `rol`;

DELETE FROM `parroquia`;


-- Reiniciar los AUTO_INCREMENT

ALTER TABLE `contacto` AUTO_INCREMENT = 1;
ALTER TABLE `intenciones` AUTO_INCREMENT = 1;
ALTER TABLE `citas` AUTO_INCREMENT = 1;
ALTER TABLE `evento` AUTO_INCREMENT = 1;
ALTER TABLE `grupos` AUTO_INCREMENT = 1;
ALTER TABLE `horarios` AUTO_INCREMENT = 1;
ALTER TABLE `users` AUTO_INCREMENT = 1;
ALTER TABLE `rol` AUTO_INCREMENT = 1;
ALTER TABLE `parroquia` AUTO_INCREMENT = 1;


-- Verificar las tablas

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
