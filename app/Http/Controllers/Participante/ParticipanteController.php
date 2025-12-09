<?php

namespace App\Http\Controllers\Participante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, Equipo, Evento, Proyecto, Participante};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;


class ParticipanteController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Carga eficiente de datos
        $user->load([
            'participante.equipos.proyecto.evento.criterios',
            'participante.equipos.proyecto.calificaciones',
            'participante.equipos.participantes.user'
        ]);

        $participante = $user->participante;
        $equipo = $participante ? $participante->equipos->first() : null;
        $proyecto = $equipo ? $equipo->proyecto : null;

        // Solicitudes pendientes (si es líder)
        $solicitudes_pendientes = [];
        if ($equipo) {
            // Verificar si es líder
            $lider = $equipo->getLider();
            $es_lider = $lider && $lider->id === $participante->id;
            
            if ($es_lider) {
                $solicitudes_pendientes = $equipo->solicitudesPendientes()
                    ->with(['participante.user', 'participante.carrera', 'perfilSugerido'])
                    ->get();
            }
        }

        // Invitaciones pendientes (para todos los participantes)
        $invitaciones_pendientes = $participante ? $participante->invitacionesPendientes()
            ->with(['equipo.proyecto', 'enviadaPor.user', 'perfilSugerido'])
            ->get() : collect();

        // Variables iniciales
        $chartLabels = [];
        $chartData = []; // Datos crudos (0-10) para el gráfico visual
        $puntajeTotal = 0; // Puntaje ponderado real para el texto final
        $evento_inscrito = null;
        $eventos_disponibles_count = 0;

        // 2. Lógica Principal
        if ($proyecto && $proyecto->evento) {
            $evento_inscrito = $proyecto->evento;

            // --- LÓGICA DE CÁLCULO IDÉNTICA AL ADMIN ---
            // Agrupamos calificaciones por criterio para no hacer queries en el loop
            $calificacionesAgrupadas = $proyecto->calificaciones->groupBy('criterio_id');

            foreach ($evento_inscrito->criterios as $criterio) {
                // A. Etiquetas para el gráfico
                $chartLabels[] = $criterio->nombre;

                // B. Obtener notas de este criterio
                $notas = $calificacionesAgrupadas->get($criterio->id);

                // C. Calcular promedio crudo (Ej: 8.5 sobre 10)
                $promedio = ($notas && $notas->count() > 0) ? $notas->avg('puntuacion') : 0;

                // D. Guardar dato para el Gráfico (Usamos el promedio crudo para que el radar se vea equilibrado)
                $chartData[] = round($promedio, 1);

                // E. Calcular Puntaje Real Ponderado (Fórmula del Admin)
                // Fórmula: (Promedio * Porcentaje) / 100
                // Ej: (10 * 50) / 100 = 5 puntos reales
                $puntosReales = ($promedio * $criterio->ponderacion) / 100;

                $puntajeTotal += $puntosReales;
            }

            // Opcional: Si quieres que el total se vea sobre 100 en vez de sobre 10, multiplica por 10 aquí.
            // $puntajeTotal = $puntajeTotal * 10; 

        } else {
            // CASO: SIN EQUIPO
            $eventos_disponibles_count = Evento::where('fecha_fin', '>=', now())->count();
        }

        $eventos_proximos = Evento::where('fecha_fin', '>=', now())
            ->orderBy('fecha_inicio', 'asc')
            ->take(3) // Solo los 3 más cercanos
            ->get();

        if ($proyecto && $proyecto->evento) {
            // ... (Lógica de Gráfico y Puntaje igual que antes) ...
            $evento_inscrito = $proyecto->evento;
            // ...
        } else {
            $eventos_disponibles_count = Evento::where('fecha_fin', '>=', now())->count();
        }

        return view('participante.dashboard', compact(
            'equipo',
            'proyecto',
            'evento_inscrito',
            'eventos_disponibles_count',
            'chartLabels',
            'chartData',
            'puntajeTotal', // Este ahora es el cálculo exacto ponderado
            'eventos_proximos',
            'solicitudes_pendientes',
            'invitaciones_pendientes'
        ));
    }

    public function generarConstancia($tipo)
    {
        $user = Auth::user();
        $participante = $user->participante;
        $equipo = $participante ? $participante->equipos->first() : null;
        $proyecto = $equipo ? $equipo->proyecto : null;

        if (!$proyecto || !$proyecto->evento) {
            return abort(404, 'No hay proyecto disponible.');
        }

        $evento = $proyecto->evento;

        // 1. CALCULAR RANKING (Misma lógica exacta que el Admin para consistencia)
        $todosProyectos = Proyecto::where('evento_id', $evento->id)->with('calificaciones')->get();

        $ranking = $todosProyectos->map(function ($p) use ($evento) {
            $totalPuntos = 0;
            $calificacionesAgrupadas = $p->calificaciones->groupBy('criterio_id');
            foreach ($evento->criterios as $criterio) {
                $notas = $calificacionesAgrupadas->get($criterio->id);
                if ($notas && $notas->count() > 0) {
                    $totalPuntos += ($notas->avg('puntuacion') * $criterio->ponderacion) / 100;
                }
            }
            return ['id' => $p->id, 'puntaje' => $totalPuntos];
        })->sortByDesc('puntaje')->values();

        // Determinar posición
        $index = $ranking->search(fn($item) => $item['id'] === $proyecto->id);
        $miLugar = $index !== false ? $index + 1 : 999;

        $textoLogro = match ($miLugar) {
            1 => 'PRIMER LUGAR',
            2 => 'SEGUNDO LUGAR',
            3 => 'TERCER LUGAR',
            default => 'PARTICIPACIÓN'
        };

        // 2. CONFIGURAR DATOS SEGÚN TIPO DE CONSTANCIA
        if ($tipo === 'individual') {
            // A nombre del alumno, SIN lista de integrantes
            $nombreTitular = $user->name;
            $mostrarIntegrantes = false;
            $archivoSalida = 'Constancia_Individual_' . $user->name . '.pdf';
        } else {
            // A nombre del equipo, CON lista de integrantes
            $nombreTitular = $equipo->nombre;
            $mostrarIntegrantes = true;
            $archivoSalida = 'Constancia_Equipo_' . $equipo->nombre . '.pdf';
        }

        // 3. GENERAR PDF (Usando stream para ver en navegador)
        // Nota: Usaremos una vista nueva o adaptada para manejar la variable $nombreTitular
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('participante.constancia_pdf', compact(
            'proyecto',
            'textoLogro',
            'nombreTitular',
            'mostrarIntegrantes',
            'evento'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream($archivoSalida);
    }

    public function descargarConstancia()
    {
        $user = Auth::user();
        $participante = $user->participante;
        $equipo = $participante ? $participante->equipos->first() : null;
        $proyecto = $equipo ? $equipo->proyecto : null;

        // 1. Validaciones de Seguridad
        if (!$proyecto || !$proyecto->evento) {
            return back()->with('error', 'No tienes un proyecto registrado para generar constancia.');
        }

        // Solo permitir si el evento ya terminó (Opcional, según tu regla de negocio)
        /*
    if ($proyecto->evento->fecha_fin > now()) {
        return back()->with('error', 'Las constancias estarán disponibles al finalizar el evento.');
    }
    */

        $evento = $proyecto->evento;

        // 2. CALCULAR RANKING GENERAL DEL EVENTO
        // Necesitamos comparar este proyecto contra todos para saber la posición real
        $todosProyectos = Proyecto::where('evento_id', $evento->id)
            ->with(['calificaciones', 'equipo'])
            ->get();

        $ranking = $todosProyectos->map(function ($p) use ($evento) {
            $totalPuntos = 0;
            $calificacionesAgrupadas = $p->calificaciones->groupBy('criterio_id');

            foreach ($evento->criterios as $criterio) {
                $notas = $calificacionesAgrupadas->get($criterio->id);
                if ($notas && $notas->count() > 0) {
                    $promedio = $notas->avg('puntuacion');
                    $totalPuntos += ($promedio * $criterio->ponderacion) / 100;
                }
            }
            return [
                'id' => $p->id,
                'puntaje' => $totalPuntos
            ];
        })->sortByDesc('puntaje')->values(); // Ordenamos de mayor a menor

        // 3. ENCONTRAR MI POSICIÓN
        // Buscamos el índice de mi proyecto en la colección ordenada
        $miPosicionIndex = $ranking->search(function ($item) use ($proyecto) {
            return $item['id'] === $proyecto->id;
        });

        // Sumamos 1 porque el array empieza en 0 (Indice 0 = Lugar 1)
        $miLugar = $miPosicionIndex !== false ? $miPosicionIndex + 1 : 999;

        // 4. DETERMINAR TEXTO DEL LOGRO 
        $textoLogro = match ($miLugar) {
            1 => 'PRIMER LUGAR',
            2 => 'SEGUNDO LUGAR',
            3 => 'TERCER LUGAR',
            default => 'PARTICIPACIÓN DESTACADA' // Para el resto (4to en adelante)
        };

        // 5. GENERAR PDF (Reutilizando la vista del Admin)
        // Usamos 'admin.resultados.pdf' porque el diseño es el mismo
        $pdf = Pdf::loadView('admin.resultados.pdf', compact('proyecto', 'textoLogro'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Constancia_' . $proyecto->equipo->nombre . '.pdf');
    }
}
