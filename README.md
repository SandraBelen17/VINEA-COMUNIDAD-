# Vinea Comunidad

Sitio web de la **Parroquia María Madre de Dios** (Diócesis de Tehuacán, Puebla).
Proyecto Integrador · Aplicaciones Web · Prof. Yedid Curioca Varela.

**Tecnología:** Laravel 12 · Blade · MySQL · CSS propio (sin frameworks) · íconos Lucide.

## Roles

| Rol | Quién | Qué puede hacer |
|---|---|---|
| Administrador | Párroco y equipo | Todo: aprueba/rechaza citas, mensajes, usuarios y datos de la parroquia |
| Editor | Sacristán | Eventos, grupos, horarios e intenciones; **ve** las citas pero no las cambia |
| Cliente | Feligrés | Se registra solo, pide citas, ve las suyas y deja intenciones |

## Instalar en tu computadora (XAMPP)

1. Clona el repo y entra a la carpeta.
2. `composer install`
3. Copia `.env.example` como `.env` y ejecuta `php artisan key:generate`.
4. En phpMyAdmin importa `database/sql/06-todo-junto.sql` (crea la base `VineaWeb_base`).
5. Revisa en `.env` el usuario y la contraseña de MySQL (`DB_USERNAME`, `DB_PASSWORD`).
6. `php artisan serve` y abre http://127.0.0.1:8000

**Usuarios de prueba**

| Correo | Contraseña | Rol |
|---|---|---|
| admin@vinea.com | 12345 | Administrador |
| editor@vinea.com | 678910 | Editor |
| cliente@vinea.com | 456321 | Cliente |

> La base de datos **no** usa migraciones de Laravel: se crea con los scripts de `database/sql/` (ver `database/sql/LEEME.md`).

## Estructura principal

```
app/Http/Controllers/AuthController.php   Iniciar sesión, crear cuenta, cerrar sesión
app/Http/Controllers/PanelController.php  Resumen del panel
app/Http/Middleware/CheckRole.php         Middleware 'rol' (protege rutas por rol)
app/Models/User.php, Rol.php              Modelos (tablas users, rol, rol_usuarios)
config/panel.php                          Opciones del menú del panel y qué rol las ve
resources/views/layouts/                  publico, panel y acceso (login/registro)
public/css/vinea.css                      Estilos con variables (colores y fuentes)
public/js/vinea.js                        Menú del celular, slider e íconos
routes/web.php                            Rutas del sitio y del panel
```

## Cambiar colores o fuentes

Todo está en las variables de `:root` al inicio de `public/css/vinea.css`.
Para cambiar las fuentes, cambia también el enlace de Google Fonts en `resources/views/partials/head.blade.php`.
Fuentes actuales: **Rethink Sans** (títulos) y **Spectral** (texto).

## Subir a InfinityFree

1. En phpMyAdmin de InfinityFree importa `database/sql/06-todo-junto-infinityfree.sql`.
2. En `.env` pon los datos de MySQL que da InfinityFree, `APP_ENV=production`, `APP_DEBUG=false`
   y tu dominio en `APP_URL`. Deja `SESSION_DRIVER=file` y `CACHE_STORE=file`.
3. Sube el proyecto **con la carpeta `vendor`** (InfinityFree no tiene Composer).
