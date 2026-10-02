<?php
// Único punto de entrada: decide qué controlador atiende cada ruta (?r=...)

require __DIR__ . '/../app/bootstrap.php';

$rutas = [
    'calendario'     => [CalendarioController::class, 'index'],
    'dia'            => [CalendarioController::class, 'dia'],

    'evento/nuevo'   => [EventoController::class, 'nuevo'],
    'evento/ver'     => [EventoController::class, 'ver'],
    'evento/editar'  => [EventoController::class, 'editar'],
    'evento/borrar'  => [EventoController::class, 'borrar'],

    'login'          => [AuthController::class, 'login'],
    'logout'         => [AuthController::class, 'logout'],

    'admin'          => [AdminController::class, 'index'],
    'admin/crear'    => [AdminController::class, 'crear'],
    'admin/resetear' => [AdminController::class, 'resetear'],
    'admin/borrar'   => [AdminController::class, 'borrar'],
    'admin/password' => [AdminController::class, 'password'],
];

$ruta = $_GET['r'] ?? 'calendario';

if (!isset($rutas[$ruta])) {
    noEncontrado();
}

[$controlador, $metodo] = $rutas[$ruta];
(new $controlador())->$metodo();
