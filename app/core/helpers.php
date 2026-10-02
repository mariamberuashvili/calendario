<?php
// Funciones de ayuda usadas en toda la aplicación

/** Escapa texto para mostrarlo en HTML */
function e($texto)
{
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

/** Genera una URL interna: url('calendario', ['mes' => 3]) */
function url($ruta, array $params = [])
{
    return 'index.php?' . http_build_query(['r' => $ruta] + $params);
}

/** Redirige a una ruta interna y termina la ejecución */
function redirigir($ruta, array $params = [])
{
    header('Location: ' . url($ruta, $params));
    exit;
}

/** Muestra una vista dentro del layout general */
function vista($nombre, array $datos = [])
{
    $datos['flash'] = obtenerFlash();
    extract($datos);

    require VIEW_PATH . '/layout/header.php';
    require VIEW_PATH . '/' . $nombre . '.php';
    require VIEW_PATH . '/layout/footer.php';
}

/** Muestra la página 404 y termina */
function noEncontrado()
{
    http_response_code(404);
    vista('errores/404', ['titulo' => 'No encontrado']);
    exit;
}

/** Guarda un mensaje para mostrarlo tras la siguiente redirección */
function flash($tipo, $mensaje)
{
    $_SESSION['flash'][] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

function obtenerFlash()
{
    $mensajes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $mensajes;
}

/** Corta la petición si no es POST */
function soloPost()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Método no permitido');
    }
}

/** Comprueba que una cadena sea una fecha real con formato AAAA-MM-DD */
function fechaValida($fecha)
{
    $d = DateTime::createFromFormat('Y-m-d', (string)$fecha);
    return $d && $d->format('Y-m-d') === $fecha;
}

/** "jueves, 15 de octubre de 2026" */
function fechaLarga($fecha)
{
    $dias = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
    $meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio',
              'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    $t = strtotime($fecha);
    return $dias[date('w', $t)] . ', ' . date('j', $t) . ' de ' . $meses[date('n', $t)] . ' de ' . date('Y', $t);
}

/** "Todo el día", "10:30 – 12:00" o "10:30" */
function horarioEvento(array $ev)
{
    if ($ev['todo_el_dia']) {
        return 'Todo el día';
    }
    $texto = substr((string)$ev['hora_inicio'], 0, 5);
    if (!empty($ev['hora_fin'])) {
        $texto .= ' – ' . substr($ev['hora_fin'], 0, 5);
    }
    return $texto;
}

/** ¿Existe esta imagen en public/img? (el logo es opcional y no está en el repositorio) */
function hayImagen($archivo)
{
    return is_file(BASE_PATH . '/public/img/' . $archivo);
}
