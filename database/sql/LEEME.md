# Base de datos (VineaWeb_base)

La base **no** se crea con migraciones de Laravel: se crea con estos scripts.

| Script | Para qué sirve |
|---|---|
| 01-crear-basedatos.sql | Crea la base `VineaWeb_base` |
| 02-crear-tablas.sql | Crea las 10 tablas |
| 03-crear-FK.sql | Crea las llaves foráneas |
| 04-datos-de-prueba.sql | Datos de prueba (3 usuarios, roles, parroquia...) |
| 05-consultas.sql | Consultas de ejemplo |
| 06-todo-junto.sql | Todo lo anterior en un solo archivo (local, XAMPP) |
| 06-todo-junto-infinityfree.sql | Igual, pero sin CREATE DATABASE/USE (para phpMyAdmin de InfinityFree) |
| 07-borrar-datos-prueba.sql | Borra los datos de prueba |

Usuarios de prueba: admin@vinea.com / 12345 · editor@vinea.com / 678910 · cliente@vinea.com / 456321
