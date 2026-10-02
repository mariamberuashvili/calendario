<?php
// Botones Modificar / Eliminar de un evento (solo con sesión iniciada).
// Necesita $ev; $compacto = true para mostrar solo los iconos.
if (!Auth::haySesion()) return;
?>
<span class="botones-evento<?= !empty($compacto) ? ' compacto' : '' ?>">
    <a class="boton sec" href="<?= e(url('evento/editar', ['id' => $ev['id']])) ?>" title="Modificar">
        ✏️<?= empty($compacto) ? ' Modificar' : '' ?>
    </a>
    <form method="post" action="<?= e(url('evento/borrar')) ?>"
          onsubmit="return confirm('¿Eliminar el evento «<?= e(addslashes($ev['titulo'])) ?>»?')">
        <?= Csrf::campo() ?>
        <input type="hidden" name="id" value="<?= (int)$ev['id'] ?>">
        <button type="submit" class="peligro" title="Eliminar">🗑️<?= empty($compacto) ? ' Eliminar' : '' ?></button>
    </form>
</span>
