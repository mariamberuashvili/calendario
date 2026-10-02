<?php
// Sesión del usuario conectado (admin o usuario)

class Auth
{
    public static function usuario()
    {
        return $_SESSION['usuario'] ?? null;
    }

    public static function id()
    {
        return self::usuario()['id'] ?? null;
    }

    /** Hay alguien con sesión iniciada: puede crear, modificar y eliminar eventos */
    public static function haySesion()
    {
        return self::usuario() !== null;
    }

    /** El admin además puede gestionar usuarios */
    public static function esAdmin()
    {
        return (self::usuario()['rol'] ?? '') === 'admin';
    }

    public static function iniciar(array $usuario)
    {
        session_regenerate_id(true);
        $_SESSION['usuario'] = [
            'id'      => (int)$usuario['id'],
            'usuario' => $usuario['usuario'],
            'rol'     => $usuario['rol'],
        ];
    }

    public static function cerrar()
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function requiereLogin()
    {
        if (!self::haySesion()) {
            flash('error', 'Inicia sesión para hacer cambios en el calendario.');
            redirigir('login');
        }
    }

    public static function requiereAdmin()
    {
        self::requiereLogin();
        if (!self::esAdmin()) {
            redirigir('calendario');
        }
    }
}
