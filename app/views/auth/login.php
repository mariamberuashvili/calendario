<form method="post" action="<?= e(url('login')) ?>" class="caja login">
    <?php if (hayImagen('logo.png')): ?><img class="logo-login" src="img/logo.png" alt="Logo"><?php endif; ?>
    <h1>Iniciar sesión</h1>
    <?php require VIEW_PATH . '/partials/mensajes.php'; ?>
    <?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
    <?= Csrf::campo() ?>
    <label>Usuario <input type="text" name="usuario" required autofocus></label>
    <label>Contraseña <input type="password" name="password" required></label>
    <button type="submit">Entrar</button>
    <p class="centro"><a href="<?= e(url('calendario')) ?>">&larr; Volver al calendario</a></p>
</form>
