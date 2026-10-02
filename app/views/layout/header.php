<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo ?? 'Calendario') ?></title>
    <?php if (hayImagen('favicon.png')): ?><link rel="icon" type="image/png" href="img/favicon.png"><?php endif; ?>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="<?= e($claseBody ?? '') ?>">
<?php if (empty($claseBody)): ?>
<nav>
    <a class="logo" href="<?= e(url('calendario')) ?>">
        <?php if (hayImagen('logo-blanco.png')): ?>
            <img src="img/logo-blanco.png" alt="Logo">
            <span>Calendario</span>
        <?php else: ?>
            📅 Calendario
        <?php endif; ?>
    </a>

    <details class="menu">
        <summary>☰ Menú<?= Auth::haySesion() ? ' · ' . e(Auth::usuario()['usuario']) : '' ?></summary>
        <div class="menu-lista">
            <a href="<?= e(url('calendario')) ?>">📅 Calendario</a>
            <?php if (Auth::haySesion()): ?>
                <a href="<?= e(url('evento/nuevo')) ?>">➕ Añadir evento</a>
                <?php if (Auth::esAdmin()): ?>
                    <a href="<?= e(url('admin')) ?>">👥 Administrar usuarios</a>
                <?php endif; ?>
                <hr>
                <form method="post" action="<?= e(url('logout')) ?>">
                    <?= Csrf::campo() ?>
                    <button type="submit">🚪 Cerrar sesión</button>
                </form>
            <?php else: ?>
                <hr>
                <a href="<?= e(url('login')) ?>">🔒 Iniciar sesión</a>
            <?php endif; ?>
        </div>
    </details>
</nav>
<?php endif; ?>
