<main>
    <section class="caja calendario">
        <?php require VIEW_PATH . '/partials/mensajes.php'; ?>

        <div class="cabecera">
            <a class="boton sec" href="<?= e(url('calendario', $anterior)) ?>" title="Mes anterior">&laquo;</a>
            <h2><?= e($nombreMes) ?> <?= $anio ?></h2>
            <a class="boton sec" href="<?= e(url('calendario', $siguiente)) ?>" title="Mes siguiente">&raquo;</a>
        </div>

        <div class="grid">
            <?php foreach (['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $d): ?>
                <div class="dia-nombre"><?= $d ?></div>
            <?php endforeach; ?>

            <?php for ($i = 1; $i < $primerDia; $i++): ?>
                <div class="dia vacio"></div>
            <?php endfor; ?>

            <?php for ($dia = 1; $dia <= $diasMes; $dia++):
                $fecha = sprintf('%04d-%02d-%02d', $anio, $mes, $dia);
                // Día con eventos: abre la vista del día. Día vacío: añadir evento (solo con sesión)
                $destino = null;
                if (!empty($eventosPorDia[$dia])) {
                    $destino = url('dia', ['fecha' => $fecha]);
                } elseif ($haySesion) {
                    $destino = url('evento/nuevo', ['fecha' => $fecha]);
                } ?>
                <div class="dia<?= $fecha === $hoy ? ' hoy' : '' ?><?= $destino ? ' clicable' : '' ?>"
                     <?php if ($destino): ?>data-url="<?= e($destino) ?>"<?php endif; ?>>
                    <span class="num"><?= $dia ?></span>
                    <?php foreach ($eventosPorDia[$dia] ?? [] as $ev): ?>
                        <a class="evento" href="<?= e(url('evento/ver', ['id' => $ev['id']])) ?>"
                           style="background: <?= e(Evento::COLORES[$ev['color']] ?? Evento::COLORES['azul']) ?>"
                           title="<?= e(horarioEvento($ev) . ' · ' . $ev['titulo'] . ' · ' . $ev['nombre']) ?>">
                            <?php if (!$ev['todo_el_dia']): ?><b><?= e(substr($ev['hora_inicio'], 0, 5)) ?></b><?php endif; ?>
                            <?= e($ev['titulo']) ?>
                            <small>· <?= e($ev['nombre']) ?></small>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endfor; ?>
        </div>
    </section>
</main>
