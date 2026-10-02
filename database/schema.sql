-- Estructura de la base de datos.
-- Se ejecuta automáticamente la primera vez que se abre la aplicación.

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'usuario') NOT NULL DEFAULT 'usuario',
    creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    nombre VARCHAR(100) NOT NULL,          -- quién creó el evento
    fecha DATE NOT NULL,
    todo_el_dia TINYINT(1) NOT NULL DEFAULT 0,
    hora_inicio TIME NULL,
    hora_fin TIME NULL,
    ubicacion VARCHAR(150) NULL,
    descripcion TEXT NULL,
    color VARCHAR(20) NOT NULL DEFAULT 'azul',
    creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
