<?php
// Protección de formularios contra CSRF

class Csrf
{
    public static function token()
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }

    /** Campo oculto para incluir en cada formulario */
    public static function campo()
    {
        return '<input type="hidden" name="csrf" value="' . e(self::token()) . '">';
    }

    public static function verificar()
    {
        if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
            http_response_code(400);
            exit('Token de seguridad inválido. Vuelve atrás y recarga la página.');
        }
    }
}
