<main class="estrecho">
    <section class="caja">
        <?php require VIEW_PATH . '/partials/mensajes.php'; ?>

        <div class="cabecera">
            <a class="boton sec" href="<?= e(url('dia', ['fecha' => $anterior])) ?>" title="Día anterior">&laquo;</a>
            <h2><?= e(ucfirst(fechaLarga($fecha))) ?></h2>
            <a class="boton sec" href="<?= e(url('dia', ['fecha' => $siguiente])) ?>" title="Día siguiente">&raquo;</a>
        </div>

        <div class="acciones barra-dia">
            <a class="boton sec" href="<?= e(url('calendario', ['mes' => $mes, 'anio' => $anio])) ?>">&larr; Volver al mes</a>
            <?php if (Auth::haySesion()): ?>
                <a class="boton" href="<?= e(url('evento/nuevo', ['fecha' => $fecha])) ?>">+ Añadir evento</a>
            <?php endif; ?>
        </div>

        <?php if (!$hayEventos): ?>
            <p class="ayuda">No hay eventos este día.</p>
        <?php endif; ?>

        <?php if ($todoElDia): ?>
            <div class="todo-dia">
                <span class="etiqueta">Todo el día</span>
                <?php foreach ($todoElDia as $ev): ?>
                    <div class="chip" style="background: <?= e(Evento::COLORES[$ev['color']] ?? Evento::COLORES['azul']) ?>">
                        <a href="<?= e(url('evento/ver', ['id' => $ev['id']])) ?>">
                            <?= e($ev['titulo']) ?> <small>· <?= e($ev['nombre']) ?></small>
                        </a>
                        <?php $compacto = true; require VIEW_PATH . '/partials/botones-evento.php'; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="horario" style="height: <?= ($hasta - $desde) * $altoHora ?>px">
            <?php for ($h = $desde; $h < $hasta; $h++): ?>
                <div class="hora" style="top: <?= ($h - $desde) * $altoHora ?>px">
                    <span><?= sprintf('%02d:00', $h) ?></span>
                </div>
            <?php endfor; ?>

            <div class="carril">
                <?php foreach ($bloques as $b): $ev = $b['evento']; ?>
                    <div class="bloque"
                         title="<?= e(horarioEvento($ev) . ' · ' . $ev['titulo'] . ' · ' . $ev['nombre']) ?>"
                         style="top: <?= $b['top'] ?>px; height: <?= $b['alto'] ?>px;
                                left: <?= $b['col'] / $b['cols'] * 100 ?>%; width: <?= 100 / $b['cols'] ?>%;
                                background: <?= e(Evento::COLORES[$ev['color']] ?? Evento::COLORES['azul']) ?>">
                        <?php $compacto = true; require VIEW_PATH . '/partials/botones-evento.php'; ?>
                        <a href="<?= e(url('evento/ver', ['id' => $ev['id']])) ?>">
                            <b><?= e(horarioEvento($ev)) ?></b>
                            <?= e($ev['titulo']) ?>
                            <small>👤 <?= e($ev['nombre']) ?><?= $ev['ubicacion'] ? ' · 📍 ' . e($ev['ubicacion']) : '' ?></small>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>
