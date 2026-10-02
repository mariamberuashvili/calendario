<?php
// Iniciar y cerrar sesión (admins y usuarios)

class AuthController
{
    public function login()
    {
        if (Auth::haySesion()) {
            redirigir('calendario');
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verificar();
            $u = Usuario::buscarPorNombre(trim($_POST['usuario'] ?? ''));

            if ($u && password_verify($_POST['password'] ?? '', $u['password'])) {
                Auth::iniciar($u);
                flash('ok', 'Hola, ' . $u['usuario'] . '.');
                redirigir('calendario');
            }
            $error = 'Usuario o contraseña incorrectos';
        }

        vista('auth/login', [
            'titulo'    => 'Iniciar sesión',
            'claseBody' => 'centrado',
            'error'     => $error,
        ]);
    }

    public function logout()
    {
        soloPost();
        Csrf::verificar();
        Auth::cerrar();
        redirigir('calendario');
    }
}
