// Menú desplegable: cerrarlo al hacer clic fuera
document.addEventListener('click', function (ev) {
    document.querySelectorAll('details.menu[open]').forEach(function (menu) {
        if (!menu.contains(ev.target)) menu.removeAttribute('open');
    });
});

// Calendario: clic en un día abre "Añadir evento" con esa fecha
document.querySelectorAll('.dia[data-url]').forEach(function (celda) {
    celda.addEventListener('click', function (ev) {
        if (ev.target.closest('a')) return;
        window.location.href = celda.dataset.url;
    });
});

// Formulario de evento: ocultar las horas si es "Todo el día"
var todoElDia = document.getElementById('todo_el_dia');
if (todoElDia) {
    var horas = document.getElementById('horas');
    var actualizar = function () { horas.hidden = todoElDia.checked; };
    todoElDia.addEventListener('change', actualizar);
    actualizar();
}
