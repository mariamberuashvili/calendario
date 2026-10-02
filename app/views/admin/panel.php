<main>
    <?php require VIEW_PATH . '/partials/mensajes.php'; ?>

    <section class="caja">
        <h2>Crear usuario</h2>
        <form method="post" action="<?= e(url('admin/crear')) ?>" class="fila">
            <?= Csrf::campo() ?>
            <input type="text" name="usuario" placeholder="Nombre de usuario" maxlength="50" required>
            <input type="text" name="password" placeholder="Contraseña (mín. 6)" minlength="6" required autocomplete="off">
            <select name="rol">
                <option value="usuario">Usuario (crea y modifica eventos)</option>
                <option value="admin">Admin (además gestiona usuarios)</option>
            </select>
            <button type="submit">Crear</button>
        </form>
    </section>

    <section class="caja">
        <h2>Usuarios (<?= count($usuarios) ?>)</h2>
        <div class="tabla-scroll">
        <table>
            <tr><th>Usuario</th><th>Rol</th><th>Creado</th><th>Nueva contraseña</th><th></th></tr>
            <?php foreach ($usuarios as $u): $soyYo = (int)$u['id'] === $yoId; ?>
            <tr>
                <td><?= e($u['usuario']) ?><?= $soyYo ? ' <small>(tú)</small>' : '' ?></td>
                <td><span class="rol rol-<?= e($u['rol']) ?>"><?= $u['rol'] === 'admin' ? 'Admin' : 'Usuario' ?></span></td>
                <td><?= e(date('d/m/Y', strtotime($u['creado']))) ?></td>
                <td>
                    <?php if (!$soyYo): ?>
                    <form method="post" action="<?= e(url('admin/resetear')) ?>" class="fila">
                        <?= Csrf::campo() ?>
                        <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                        <input type="text" name="password" placeholder="Nueva" minlength="6" required autocomplete="off">
                        <button type="submit" class="sec">Cambiar</button>
                    </form>
                    <?php else: ?>
                        <small>Abajo ↓</small>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!$soyYo): ?>
                    <form method="post" action="<?= e(url('admin/borrar')) ?>"
                          onsubmit="return confirm('¿Eliminar al usuario «<?= e(addslashes($u['usuario'])) ?>»? Sus eventos se mantienen.')">
                        <?= Csrf::campo() ?>
                        <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                        <button type="submit" class="peligro">🗑️ Eliminar</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div>
    </section>

    <section class="caja estrecho-caja">
        <h2>Cambiar mi contraseña</h2>
        <form method="post" action="<?= e(url('admin/password')) ?>">
            <?= Csrf::campo() ?>
            <label>Contraseña actual <input type="password" name="actual" required></label>
            <label>Nueva contraseña <input type="password" name="nueva" required minlength="6"></label>
            <label>Repite la nueva <input type="password" name="confirmar" required minlength="6"></label>
            <button type="submit">Cambiar contraseña</button>
        </form>
    </section>
</main>
