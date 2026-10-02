<?php
// Punto de arranque: carga configuración, clases y abre la sesión

define('BASE_PATH', dirname(__DIR__));
define('VIEW_PATH', BASE_PATH . '/app/views');

if (!is_file(BASE_PATH . '/config/config.php')) {
    exit('Falta config/config.php: copia config/config.example.php como config.php y pon tus datos.');
}
$config = require BASE_PATH . '/config/config.php';

require_once BASE_PATH . '/app/core/helpers.php';
require_once BASE_PATH . '/app/core/Database.php';
require_once BASE_PATH . '/app/core/Auth.php';
require_once BASE_PATH . '/app/core/Csrf.php';

require_once BASE_PATH . '/app/models/Usuario.php';
require_once BASE_PATH . '/app/models/Evento.php';

require_once BASE_PATH . '/app/controllers/CalendarioController.php';
require_once BASE_PATH . '/app/controllers/EventoController.php';
require_once BASE_PATH . '/app/controllers/AuthController.php';
require_once BASE_PATH . '/app/controllers/AdminController.php';

session_start();

Database::conectar($config);
