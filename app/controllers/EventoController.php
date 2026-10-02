<?php
// Ver eventos (cualquiera); crear, modificar y eliminar (con sesión iniciada)

class EventoController
{
    public function nuevo()
    {
        Auth::requiereLogin();

        $fecha = $_GET['fecha'] ?? '';
        $evento = Evento::vacio(fechaValida($fecha) ? $fecha : date('Y-m-d'));
        $evento['nombre'] = Auth::usuario()['usuario'];
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verificar();
            [$evento, $errores] = $this->leerFormulario();

            if (!$errores) {
                Evento::crear($evento);
                flash('ok', 'Evento añadido.');
                redirigir('dia', ['fecha' => $evento['fecha']]);
            }
        }

        vista('evento/formulario', [
            'titulo'   => 'Añadir evento',
            'evento'   => $evento,
            'errores'  => $errores,
            'accion'   => url('evento/nuevo'),
            'cancelar' => url('calendario'),
        ]);
    }

    public function ver()
    {
        $evento = Evento::buscar((int)($_GET['id'] ?? 0)) ?: noEncontrado();

        vista('evento/ver', [
            'titulo' => $evento['titulo'] . ' - Calendario',
            'evento' => $evento,
        ]);
    }

    public function editar()
    {
        Auth::requiereLogin();

        $id = (int)($_GET['id'] ?? 0);
        $evento = Evento::buscar($id) ?: noEncontrado();
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verificar();
            [$evento, $errores] = $this->leerFormulario();

            if (!$errores) {
                Evento::actualizar($id, $evento);
                flash('ok', 'Evento modificado.');
                redirigir('dia', ['fecha' => $evento['fecha']]);
            }
        }

        vista('evento/formulario', [
            'titulo'   => 'Modificar evento',
            'evento'   => $evento,
            'errores'  => $errores,
            'accion'   => url('evento/editar', ['id' => $id]),
            'cancelar' => url('evento/ver', ['id' => $id]),
        ]);
    }

    public function borrar()
    {
        soloPost();
        Auth::requiereLogin();
        Csrf::verificar();

        $evento = Evento::buscar((int)($_POST['id'] ?? 0)) ?: noEncontrado();
        Evento::eliminar($evento['id']);
        flash('ok', 'Evento "' . $evento['titulo'] . '" eliminado.');

        // Volver al día si aún le quedan eventos; si no, al mes
        if (Evento::entreFechas($evento['fecha'], $evento['fecha'])) {
            redirigir('dia', ['fecha' => $evento['fecha']]);
        }
        $t = strtotime($evento['fecha']);
        redirigir('calendario', ['mes' => date('n', $t), 'anio' => date('Y', $t)]);
    }

    /** Lee y valida el formulario. Devuelve [datos, errores] */
    private function leerFormulario()
    {
        $p = $_POST;
        $d = [
            'titulo'      => trim($p['titulo'] ?? ''),
            'nombre'      => trim($p['nombre'] ?? ''),
            'fecha'       => $p['fecha'] ?? '',
            'todo_el_dia' => isset($p['todo_el_dia']) ? 1 : 0,
            'hora_inicio' => $p['hora_inicio'] ?? '',
            'hora_fin'    => $p['hora_fin'] ?? '',
            'ubicacion'   => trim($p['ubicacion'] ?? ''),
            'descripcion' => trim($p['descripcion'] ?? ''),
            'color'       => $p['color'] ?? 'azul',
        ];
        $errores = [];

        if ($d['titulo'] === '' || mb_strlen($d['titulo']) > 150) {
            $errores[] = 'El título es obligatorio (máx. 150 caracteres).';
        }
        if ($d['nombre'] === '' || mb_strlen($d['nombre']) > 100) {
            $errores[] = 'Tu nombre es obligatorio (máx. 100 caracteres).';
        }
        if (!fechaValida($d['fecha'])) {
            $errores[] = 'La fecha no es válida.';
        }
        if (mb_strlen($d['ubicacion']) > 150) {
            $errores[] = 'La ubicación es demasiado larga (máx. 150 caracteres).';
        }
        if (!isset(Evento::COLORES[$d['color']])) {
            $d['color'] = 'azul';
        }

        if ($d['todo_el_dia']) {
            $d['hora_inicio'] = null;
            $d['hora_fin'] = null;
        } else {
            $horaValida = function ($h) { return (bool)preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $h); };

            if (!$horaValida($d['hora_inicio'])) {
                $errores[] = 'Indica la hora de inicio o marca "Todo el día".';
            }
            if ($d['hora_fin'] === '') {
                $d['hora_fin'] = null;
            } elseif (!$horaValida($d['hora_fin'])) {
                $errores[] = 'La hora de fin no es válida.';
            } elseif ($d['hora_fin'] <= $d['hora_inicio']) {
                $errores[] = 'La hora de fin debe ser posterior a la de inicio.';
            }
        }

        return [$d, $errores];
    }
}
