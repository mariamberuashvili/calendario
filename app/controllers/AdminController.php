<?php
// Panel de administración: gestión de usuarios y cambio de la propia contraseña

class AdminController
{
    public function __construct()
    {
        Auth::requiereAdmin();
    }

    public function index()
    {
        vista('admin/panel', [
            'titulo'   => 'Administrar - Calendario',
            'usuarios' => Usuario::todos(),
            'yoId'     => Auth::id(),
        ]);
    }

    public function crear()
    {
        soloPost();
        Csrf::verificar();

        $usuario = trim($_POST['usuario'] ?? '');
        $password = $_POST['password'] ?? '';
        $rol = ($_POST['rol'] ?? '') === 'admin' ? 'admin' : 'usuario';

        if ($usuario === '' || mb_strlen($usuario) > 50) {
            flash('error', 'El nombre de usuario es obligatorio (máx. 50 caracteres).');
        } elseif (strlen($password) < 6) {
            flash('error', 'La contraseña debe tener al menos 6 caracteres.');
        } elseif (Usuario::existe($usuario)) {
            flash('error', 'Ya existe un usuario llamado "' . $usuario . '".');
        } else {
            Usuario::crear($usuario, $password, $rol);
            flash('ok', ($rol === 'admin' ? 'Admin' : 'Usuario') . ' "' . $usuario . '" creado.');
        }
        redirigir('admin');
    }

    /** El admin pone una contraseña nueva a otro usuario */
    public function resetear()
    {
        soloPost();
        Csrf::verificar();

        $u = Usuario::buscarPorId((int)($_POST['id'] ?? 0)) ?: noEncontrado();
        $password = $_POST['password'] ?? '';

        if (strlen($password) < 6) {
            flash('error', 'La contraseña debe tener al menos 6 caracteres.');
        } else {
            Usuario::cambiarPassword($u['id'], $password);
            flash('ok', 'Contraseña de "' . $u['usuario'] . '" cambiada.');
        }
        redirigir('admin');
    }

    public function borrar()
    {
        soloPost();
        Csrf::verificar();

        $u = Usuario::buscarPorId((int)($_POST['id'] ?? 0)) ?: noEncontrado();

        // No se puede borrar uno mismo, así siempre queda al menos un admin
        if ((int)$u['id'] === Auth::id()) {
            flash('error', 'No puedes eliminar tu propia cuenta.');
        } else {
            Usuario::eliminar($u['id']);
            flash('ok', 'Usuario "' . $u['usuario'] . '" eliminado.');
        }
        redirigir('admin');
    }

    /** Cambiar la contraseña del admin conectado */
    public function password()
    {
        soloPost();
        Csrf::verificar();

        $yo = Usuario::buscarPorId(Auth::id());
        $nueva = $_POST['nueva'] ?? '';

        if (!$yo || !password_verify($_POST['actual'] ?? '', $yo['password'])) {
            flash('error', 'La contraseña actual no es correcta.');
        } elseif (strlen($nueva) < 6) {
            flash('error', 'La nueva contraseña debe tener al menos 6 caracteres.');
        } elseif ($nueva !== ($_POST['confirmar'] ?? '')) {
            flash('error', 'Las contraseñas nuevas no coinciden.');
        } else {
            Usuario::cambiarPassword($yo['id'], $nueva);
            flash('ok', 'Tu contraseña se ha cambiado.');
        }
        redirigir('admin');
    }
}
