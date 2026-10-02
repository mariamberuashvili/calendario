<?php
// Vista mensual del calendario (pública)

class CalendarioController
{
    const MESES = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
                   'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    public function index()
    {
        $mes = (int)($_GET['mes'] ?? date('n'));
        $anio = (int)($_GET['anio'] ?? date('Y'));
        if ($mes < 1 || $mes > 12) $mes = (int)date('n');
        if ($anio < 1970 || $anio > 2100) $anio = (int)date('Y');

        $inicio = sprintf('%04d-%02d-01', $anio, $mes);
        $fin = date('Y-m-t', strtotime($inicio));

        $eventosPorDia = [];
        foreach (Evento::entreFechas($inicio, $fin) as $ev) {
            $eventosPorDia[(int)date('j', strtotime($ev['fecha']))][] = $ev;
        }

        vista('calendario/index', [
            'titulo'        => self::MESES[$mes] . ' ' . $anio . ' - Calendario',
            'mes'           => $mes,
            'anio'          => $anio,
            'nombreMes'     => self::MESES[$mes],
            'diasMes'       => (int)date('t', strtotime($inicio)),
            'primerDia'     => (int)date('N', strtotime($inicio)), // 1 = lunes
            'hoy'           => date('Y-m-d'),
            'anterior'      => $this->desplazarMes($mes, $anio, -1),
            'siguiente'     => $this->desplazarMes($mes, $anio, 1),
            'eventosPorDia' => $eventosPorDia,
            'haySesion'     => Auth::haySesion(),
        ]);
    }

    /** Vista de un día: eventos de todo el día arriba y el resto en un horario por horas */
    public function dia()
    {
        $fecha = $_GET['fecha'] ?? '';
        if (!fechaValida($fecha)) {
            $fecha = date('Y-m-d');
        }
        $t = strtotime($fecha);

        $eventos = Evento::entreFechas($fecha, $fecha);
        $todoElDia = array_filter($eventos, function ($ev) { return $ev['todo_el_dia']; });
        $conHora = array_filter($eventos, function ($ev) { return !$ev['todo_el_dia']; });

        [$bloques, $desde, $hasta] = $this->organizarHorario(array_values($conHora));

        vista('calendario/dia', [
            'titulo'    => ucfirst(fechaLarga($fecha)) . ' - Calendario',
            'fecha'     => $fecha,
            'anterior'  => date('Y-m-d', strtotime('-1 day', $t)),
            'siguiente' => date('Y-m-d', strtotime('+1 day', $t)),
            'mes'       => (int)date('n', $t),
            'anio'      => (int)date('Y', $t),
            'hayEventos'=> (bool)$eventos,
            'todoElDia' => $todoElDia,
            'bloques'   => $bloques,
            'desde'     => $desde,
            'hasta'     => $hasta,
            'altoHora'  => self::ALTO_HORA,
        ]);
    }

    const ALTO_HORA = 48; // píxeles que ocupa una hora en la vista de día

    /**
     * Calcula la posición de cada evento en el horario.
     * Los eventos que se solapan se reparten en columnas, como en un calendario normal.
     * Devuelve [bloques, hora inicial, hora final] del horario a mostrar.
     */
    private function organizarHorario(array $eventos)
    {
        $desde = 8;
        $hasta = 20;
        $bloques = [];

        foreach ($eventos as $ev) {
            $ini = $this->minutos($ev['hora_inicio']);
            $fin = $ev['hora_fin'] ? $this->minutos($ev['hora_fin']) : $ini + 60; // sin fin: 1 hora
            $fin = min(max($fin, $ini + 30), 24 * 60);

            $desde = min($desde, intdiv($ini, 60));
            $hasta = max($hasta, (int)ceil($fin / 60));
            $bloques[] = ['evento' => $ev, 'ini' => $ini, 'fin' => $fin, 'col' => 0, 'cols' => 1];
        }

        // Agrupar eventos que se solapan y asignar a cada uno una columna
        $grupo = [];
        $columnas = []; // columna => minuto en que queda libre
        $finGrupo = -1;
        foreach ($bloques as $i => $b) {
            if ($b['ini'] >= $finGrupo) {
                foreach ($grupo as $j) $bloques[$j]['cols'] = count($columnas);
                $grupo = [];
                $columnas = [];
            }
            $col = 0;
            while (isset($columnas[$col]) && $columnas[$col] > $b['ini']) $col++;
            $columnas[$col] = $b['fin'];
            $bloques[$i]['col'] = $col;
            $grupo[] = $i;
            $finGrupo = max($finGrupo, $b['fin']);
        }
        foreach ($grupo as $j) $bloques[$j]['cols'] = count($columnas);

        foreach ($bloques as $i => $b) {
            $bloques[$i]['top'] = ($b['ini'] - $desde * 60) * self::ALTO_HORA / 60;
            $bloques[$i]['alto'] = ($b['fin'] - $b['ini']) * self::ALTO_HORA / 60;
        }

        return [$bloques, $desde, $hasta];
    }

    /** "10:30:00" → 630 */
    private function minutos($hora)
    {
        return (int)substr($hora, 0, 2) * 60 + (int)substr($hora, 3, 2);
    }

    private function desplazarMes($mes, $anio, $delta)
    {
        $mes += $delta;
        if ($mes < 1)  { $mes = 12; $anio--; }
        if ($mes > 12) { $mes = 1;  $anio++; }
        return ['mes' => $mes, 'anio' => $anio];
    }
}
