<main class="estrecho">
    <section class="caja">
        <?php require VIEW_PATH . '/partials/mensajes.php'; ?>

        <h2 class="titulo-evento">
            <span class="punto" style="background: <?= e(Evento::COLORES[$evento['color']] ?? Evento::COLORES['azul']) ?>"></span>
            <?= e($evento['titulo']) ?>
        </h2>

        <dl class="detalles">
            <dt>📅 Fecha</dt>
            <dd><?= e(ucfirst(fechaLarga($evento['fecha']))) ?></dd>

            <dt>🕒 Horario</dt>
            <dd><?= e(horarioEvento($evento)) ?></dd>

            <?php if ($evento['ubicacion'] !== null && $evento['ubicacion'] !== ''): ?>
                <dt>📍 Ubicación</dt>
                <dd><?= e($evento['ubicacion']) ?></dd>
            <?php endif; ?>

            <dt>👤 Creado por</dt>
            <dd><?= e($evento['nombre']) ?></dd>

            <?php if ($evento['descripcion'] !== null && $evento['descripcion'] !== ''): ?>
                <dt>📝 Descripción</dt>
                <dd><?= nl2br(e($evento['descripcion'])) ?></dd>
            <?php endif; ?>
        </dl>

        <div class="acciones">
            <a class="boton sec" href="<?= e(url('dia', ['fecha' => $evento['fecha']])) ?>">&larr; Volver al día</a>
            <?php $ev = $evento; $compacto = false; require VIEW_PATH . '/partials/botones-evento.php'; ?>
        </div>
    </section>
</main>
