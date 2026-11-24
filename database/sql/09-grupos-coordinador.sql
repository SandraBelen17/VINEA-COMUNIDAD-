-- Coordinador y WhatsApp de cada grupo (módulo Grupos)
-- Agrega dos columnas a `grupos`; no cambia ni borra nada de lo que ya existe.
-- Úsalo en phpMyAdmin con la base ya seleccionada (local: VineaWeb_base; InfinityFree: la del hosting).
-- Las dos columnas son opcionales: si un grupo no tiene coordinador o número, el sitio no los muestra.

ALTER TABLE `grupos`
    ADD COLUMN `coordinador` VARCHAR(150) NULL AFTER `lugar`,
    ADD COLUMN `whatsapp` VARCHAR(10) NULL AFTER `coordinador`;
