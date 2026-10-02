<?php
// Acceso a la tabla "eventos"

class Evento
{
    const COLORES = [
        'azul'    => '#2f6fdb',
        'verde'   => '#2e9d5b',
        'rojo'    => '#d9534f',
        'naranja' => '#e8891c',
        'morado'  => '#8a4fd6',
        'gris'    => '#6b7280',
    ];

    const CAMPOS = ['titulo', 'nombre', 'fecha', 'todo_el_dia', 'hora_inicio', 'hora_fin',
                    'ubicacion', 'descripcion', 'color'];

    /** Valores por defecto para el formulario de nuevo evento */
    public static function vacio($fecha)
    {
        return [
            'titulo' => '', 'nombre' => '', 'fecha' => $fecha, 'todo_el_dia' => 0,
            'hora_inicio' => '', 'hora_fin' => '', 'ubicacion' => '', 'descripcion' => '',
            'color' => 'azul',
        ];
    }

    /** Eventos entre dos fechas (incluidas): primero los de todo el día, luego por hora */
    public static function entreFechas($inicio, $fin)
    {
        $st = Database::pdo()->prepare(
            'SELECT * FROM eventos
             WHERE fecha BETWEEN ? AND ?
             ORDER BY fecha, todo_el_dia DESC, hora_inicio'
        );
        $st->execute([$inicio, $fin]);
        return $st->fetchAll();
    }

    /** Todos los eventos, los más recientes primero */
    public static function todos()
    {
        return Database::pdo()->query(
            'SELECT * FROM eventos ORDER BY fecha DESC, todo_el_dia DESC, hora_inicio'
        )->fetchAll();
    }

    public static function buscar($id)
    {
        $st = Database::pdo()->prepare('SELECT * FROM eventos WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch();
    }

    /** Crea un evento y devuelve su id */
    public static function crear(array $datos)
    {
        $columnas = implode(', ', self::CAMPOS);
        $huecos = implode(', ', array_fill(0, count(self::CAMPOS), '?'));

        $st = Database::pdo()->prepare("INSERT INTO eventos ($columnas) VALUES ($huecos)");
        $st->execute(self::valores($datos));
        return (int)Database::pdo()->lastInsertId();
    }

    public static function actualizar($id, array $datos)
    {
        $asignaciones = implode(', ', array_map(function ($c) { return "$c = ?"; }, self::CAMPOS));

        $st = Database::pdo()->prepare("UPDATE eventos SET $asignaciones WHERE id = ?");
        $st->execute(array_merge(self::valores($datos), [$id]));
    }

    public static function eliminar($id)
    {
        Database::pdo()->prepare('DELETE FROM eventos WHERE id = ?')->execute([$id]);
    }

    private static function valores(array $datos)
    {
        return array_map(function ($c) use ($datos) { return $datos[$c]; }, self::CAMPOS);
    }
}
