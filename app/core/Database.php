<?php
// Conexión PDO única y creación automática de la base de datos

class Database
{
    private static $pdo;

    public static function conectar(array $config)
    {
        $db = $config['db'];

        try {
            self::$pdo = new PDO(
                'mysql:host=' . $db['host'] . ';charset=utf8mb4',
                $db['usuario'],
                $db['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
            self::$pdo->exec('CREATE DATABASE IF NOT EXISTS `' . $db['nombre'] . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            self::$pdo->exec('USE `' . $db['nombre'] . '`');

            self::instalar($config['admin']);
        } catch (PDOException $ex) {
            http_response_code(500);
            exit('Error de base de datos: ' . e($ex->getMessage()) . '<br>¿Está MySQL iniciado en XAMPP?');
        }
    }

    public static function pdo()
    {
        return self::$pdo;
    }

    /** Crea las tablas (si faltan) y un admin (si no existe ninguno) */
    private static function instalar(array $admin)
    {
        if (!self::existeTabla('usuarios')) {
            self::$pdo->exec(file_get_contents(BASE_PATH . '/database/schema.sql'));

            // Migración desde la versión anterior, que solo tenía la tabla "admins"
            if (self::existeTabla('admins')) {
                self::$pdo->exec("INSERT INTO usuarios (usuario, password, rol, creado)
                                  SELECT usuario, password, 'admin', creado FROM admins");
                self::$pdo->exec('DROP TABLE admins');
            }
        }

        if (!Usuario::hayAdmin()) {
            Usuario::crear($admin['usuario'], $admin['password'], 'admin');
        }
    }

    private static function existeTabla($nombre)
    {
        $st = self::$pdo->prepare('SHOW TABLES LIKE ?');
        $st->execute([$nombre]);
        return (bool)$st->fetchColumn();
    }
}
