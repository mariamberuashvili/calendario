<main class="estrecho">
    <section class="caja">
        <h2><?= e($titulo) ?></h2>

        <?php if ($errores): ?>
            <div class="error">
                <?php foreach ($errores as $err): ?><div><?= e($err) ?></div><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= e($accion) ?>">
            <?= Csrf::campo() ?>

            <label>Título *
                <input type="text" name="titulo" maxlength="150" required autofocus
                       placeholder="Ej.: Reunión de equipo" value="<?= e($evento['titulo']) ?>">
            </label>

            <label>Creado por *
                <input type="text" name="nombre" maxlength="100" required
                       placeholder="Ej.: María" value="<?= e($evento['nombre']) ?>">
            </label>

            <label>Fecha *
                <input type="date" name="fecha" required value="<?= e($evento['fecha']) ?>">
            </label>

            <label class="check">
                <input type="checkbox" name="todo_el_dia" id="todo_el_dia" <?= $evento['todo_el_dia'] ? 'checked' : '' ?>>
                Todo el día
            </label>

            <div class="fila-campos" id="horas">
                <label>Hora de inicio
                    <input type="time" name="hora_inicio" value="<?= e(substr((string)$evento['hora_inicio'], 0, 5)) ?>">
                </label>
                <label>Hora de fin (opcional)
                    <input type="time" name="hora_fin" value="<?= e(substr((string)$evento['hora_fin'], 0, 5)) ?>">
                </label>
            </div>

            <label>Ubicación (opcional)
                <input type="text" name="ubicacion" maxlength="150"
                       placeholder="Ej.: Sala 2, Zoom..." value="<?= e($evento['ubicacion']) ?>">
            </label>

            <label>Descripción (opcional)
                <textarea name="descripcion" rows="4"><?= e($evento['descripcion']) ?></textarea>
            </label>

            <fieldset class="colores">
                <legend>Color</legend>
                <?php foreach (Evento::COLORES as $nombreColor => $hex): ?>
                    <label title="<?= e(ucfirst($nombreColor)) ?>">
                        <input type="radio" name="color" value="<?= e($nombreColor) ?>"
                               <?= $evento['color'] === $nombreColor ? 'checked' : '' ?>>
                        <span style="background: <?= e($hex) ?>"></span>
                    </label>
                <?php endforeach; ?>
            </fieldset>

            <div class="acciones">
                <button type="submit">Guardar evento</button>
                <a class="boton sec" href="<?= e($cancelar) ?>">Cancelar</a>
            </div>
        </form>
    </section>
</main>
