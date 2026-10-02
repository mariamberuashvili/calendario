<?php
// Copia este archivo como config.php y pon tus datos.
// Configuración general de la aplicación (valores por defecto de XAMPP)
return [
    'db' => [
        'host'    => 'localhost',
        'usuario' => 'root',
        'password'=> '',
        'nombre'  => 'calendario',
    ],

    // Único administrador. Se crea automáticamente si no existe.
    // Después se puede cambiar la contraseña desde "Administrar".
    'admin' => [
        'usuario'  => 'admin',
        'password' => 'cambia-esta-contraseña',
    ],
];
