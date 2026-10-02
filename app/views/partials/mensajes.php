<?php foreach ($flash ?? [] as $f): ?>
    <p class="<?= $f['tipo'] === 'ok' ? 'ok' : 'error' ?>"><?= e($f['mensaje']) ?></p>
<?php endforeach; ?>
