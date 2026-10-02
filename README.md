# Calendario (PHP + MySQL)

- **Visitantes** (sin iniciar sesión): solo ven el calendario y los eventos.
- **Usuarios** (creados por el admin): crean, modifican y eliminan eventos.
- **Admins**: lo mismo que un usuario y además crean y eliminan usuarios.

## Puesta en marcha (XAMPP)

1. Copiar la carpeta en `C:
mpp\htdocs\Calendario`.
2. Copiar `config/config.example.php` como `config/config.php` y poner la contraseña del admin.
3. En `C:
mpp\php\php.ini`, activar `extension=pdo_mysql` (quitar el `;`).
4. Iniciar **Apache** y **MySQL** en el panel de XAMPP.
5. Abrir <http://localhost/Calendario/>
6. Menú ☰ → **Iniciar sesión** con el admin de `config/config.php`.

Opcional: poner el logo de la empresa en `public/img/` (`logo.png` negro, `logo-blanco.png` blanco y `favicon.png`).

La base de datos `calendario`, sus tablas y el admin se crean solos la primera vez.

## Estructura

```
Calendario/
├── index.php                 Redirige a public/
├── config/config.example.php Plantilla de config.php (conexión y admin inicial)
├── database/schema.sql       Tablas (se ejecuta automáticamente)
├── app/
│   ├── bootstrap.php         Carga configuración, clases y sesión
│   ├── core/                 Database, Auth, Csrf, helpers
│   ├── models/               Usuario, Evento (consultas SQL)
│   ├── controllers/          Calendario, Evento, Auth, Admin (lógica)
│   └── views/                HTML (layout, calendario, evento, admin, login)
└── public/                   Lo único accesible desde el navegador
    ├── index.php             Router: ?r=ruta → controlador
    ├── css/style.css
    └── js/app.js
```

Las carpetas `app/`, `config/` y `database/` tienen un `.htaccess` que bloquea el acceso web.

## Añadir una página nueva

1. Método en un controlador de `app/controllers/`.
2. Vista en `app/views/`.
3. Ruta en el array `$rutas` de `public/index.php`.
