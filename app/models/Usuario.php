<?php
// Acceso a la tabla "usuarios" (admins y usuarios que pueden editar el calendario)

class Usuario
{
    public static function buscarPorNombre($usuario)
    {
        $st = Database::pdo()->prepare('SELECT * FROM usuarios WHERE usuario = ?');
        $st->execute([$usuario]);
        return $st->fetch();
    }

    public static function buscarPorId($id)
    {
        $st = Database::pdo()->prepare('SELECT * FROM usuarios WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch();
    }

    /** Todos los usuarios: primero los admins, luego por nombre */
    public static function todos()
    {
        return Database::pdo()->query(
            "SELECT id, usuario, rol, creado FROM usuarios ORDER BY rol = 'admin' DESC, usuario"
        )->fetchAll();
    }

    public static function existe($usuario)
    {
        return (bool)self::buscarPorNombre($usuario);
    }

    public static function hayAdmin()
    {
        return (bool)Database::pdo()->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'admin'")->fetchColumn();
    }

    public static function crear($usuario, $password, $rol = 'usuario')
    {
        $st = Database::pdo()->prepare('INSERT INTO usuarios (usuario, password, rol) VALUES (?, ?, ?)');
        $st->execute([$usuario, password_hash($password, PASSWORD_DEFAULT), $rol]);
    }

    public static function cambiarPassword($id, $password)
    {
        $st = Database::pdo()->prepare('UPDATE usuarios SET password = ? WHERE id = ?');
        $st->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
    }

    public static function eliminar($id)
    {
        Database::pdo()->prepare('DELETE FROM usuarios WHERE id = ?')->execute([$id]);
    }
}
