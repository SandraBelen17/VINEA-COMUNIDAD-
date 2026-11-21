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
