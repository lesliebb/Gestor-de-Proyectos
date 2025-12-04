<?php

namespace App\Http\Controllers\Participante;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Evento;
use App\Models\Perfil;
use App\Models\User;
use App\Models\SolicitudEquipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EquipoController extends Controller
{
    public function create()
    {
        if (Auth::user()->participante->equipos()->exists()) {
            return redirect()->route('participante.dashboard')
                ->with('error', 'Ya perteneces a un equipo.');
        }

        // Traemos eventos vigentes
        $eventosDisponibles = Evento::where('fecha_fin', '>=', now())
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        return view('participante.equipos.create', compact('eventosDisponibles'));
    }

    public function store(Request $request)
    {
        // ... (Validaciones anteriores se mantienen igual) ...
        $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'nombre_equipo' => 'required|string|max:50|unique:equipos,nombre',
            'nombre_proyecto' => 'required|string|max:100',
            'descripcion_proyecto' => 'required|string|max:500',
            'repositorio_url' => 'nullable|url|max:255',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = Auth::user();
                // Aseguramos obtener el modelo Participante fresco
                $participante = $user->participante;

                if (!$participante) {
                    throw new \Exception("No tienes un perfil de participante registrado.");
                }

                // 1. Crear Equipo
                $equipo = Equipo::create([
                    'nombre' => $request->nombre_equipo,
                ]);

                // 2. Crear Proyecto
                $equipo->proyecto()->create([
                    'nombre' => $request->nombre_proyecto,
                    'descripcion' => $request->descripcion_proyecto,
                    'repositorio_url' => $request->repositorio_url,
                    'evento_id' => $request->evento_id,
                ]);

                // 3. Obtener ID del Perfil Líder
                $perfilLider = \App\Models\Perfil::where('nombre', 'Líder de Proyecto')->first();
                $idLider = $perfilLider ? $perfilLider->id : 3;

                // 4. VINCULACIÓN EXPLÍCITA
                $equipo->participantes()->attach($participante->id, [
                    'perfil_id' => $idLider,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

            // Forzamos recarga de la relación para la siguiente vista
            $user = User::with('participante.equipos')->find(Auth::id());
            if ($user) {
                Auth::setUser($user);
            }

            // Redirigimos a la vista de GESTIÓN DE EQUIPO directamente para que agregue miembros
            return redirect()->route('participante.equipos.edit')
                ->with('success', 'Equipo creado. Ahora agrega a tus compañeros (Mínimo 2 integrantes).');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function showJoinForm(Request $request)
    {
        $eventos = Evento::where('fecha_fin', '>=', now())->get();
        $perfiles = Perfil::where('nombre', '!=', 'Líder de Proyecto')->get();

        // Iniciamos la consulta base
        $query = Equipo::with(['proyecto.evento']) // Cargamos la relación anidada
            ->withCount('participantes');

        // --- CORRECCIÓN DE FILTRO ---
        // Filtramos Equipos que tengan un Proyecto en X evento
        if ($request->has('evento_id') && $request->evento_id != '') {
            $query->whereHas('proyecto', function ($q) use ($request) {
                $q->where('evento_id', $request->evento_id);
            });
        } else {
            // Por defecto: Equipos cuyo proyecto sea de un evento vigente
            $query->whereHas('proyecto', function ($q) use ($eventos) {
                $q->whereIn('evento_id', $eventos->pluck('id'));
            });
        }

        $equiposDisponibles = $query->get();

        return view('participante.equipos.join', compact('equiposDisponibles', 'eventos', 'perfiles'));
    }

    public function join(Request $request)
    {
        $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'mensaje' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $participante = $user->participante;

        // Validación 1: Que no esté en otro equipo
        if ($participante->equipos()->exists()) {
            return back()->with('error', 'Ya tienes equipo.');
        }

        $equipo = Equipo::find($request->equipo_id);

        // Validación 2: Que el equipo no esté lleno
        if ($equipo->participantes()->count() >= 5) {
            return back()->with('error', 'Equipo lleno.');
        }

        // Validación 3: Que no tenga solicitud PENDIENTE para este equipo
        $solicitudPendiente = SolicitudEquipo::where('equipo_id', $equipo->id)
            ->where('participante_id', $participante->id)
            ->where('estado', 'pendiente')
            ->exists();

        if ($solicitudPendiente) {
            return back()->with('error', 'Ya tienes una solicitud pendiente para este equipo. Espera a que el líder responda.');
        }

        // Redirigir al método de crear solicitud
        return redirect()->route('participante.solicitudes.crear.form', $equipo)
            ->with('mensaje', $request->mensaje);
    }

    public function edit()
    {
        $participante = Auth::user()->participante;
        $equipo = $participante->equipos()->with(['proyecto.evento', 'participantes.carrera', 'participantes.user'])->first();

        if (!$equipo) {
            return redirect()->route('participante.dashboard')->with('error', 'No tienes equipo para gestionar.');
        }

        // Validar si es líder (opcional, pero recomendado)
        $esLider = $equipo->participantes()
            ->where('participantes.id', $participante->id)
            ->wherePivot('perfil_id', 3) // Asumiendo 3 es líder
            ->exists();

        if (!$esLider) {
            return redirect()->route('participante.dashboard')->with('error', 'Solo el líder puede gestionar el equipo.');
        }

        // CANDIDATOS: Alumnos activos, QUE NO TENGAN EQUIPO, excluyendo al usuario actual
        $candidatos = \App\Models\Participante::whereDoesntHave('equipos') // Que no estén en ningún equipo
            ->with(['user', 'carrera'])
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->user->name,
                    'no_control' => $p->no_control,
                    'carrera' => $p->carrera->nombre
                ];
            });

        // Perfiles disponibles (Roles para miembros) - Excluyendo Líder
        $perfiles = \App\Models\Perfil::where('nombre', '!=', 'Líder de Proyecto')->get();

        return view('participante.equipos.edit', compact('equipo', 'candidatos', 'perfiles'));
    }

    public function show($id)
    {
        $equipo = Equipo::with(['proyecto.evento', 'participantes.carrera', 'participantes.user'])->findOrFail($id);
        
        return view('participante.equipos.show', compact('equipo'));
    }

    public function update(Request $request, $id)
    {
        $equipo = Equipo::findOrFail($id);

        // Seguridad: Verificar que el usuario sea el líder de este equipo
        if (Auth::user()->participante->equipos->first()->id !== $equipo->id) {
            abort(403);
        }

        $request->validate([
            // Validamos que el nombre sea único, ignorando el ID del equipo actual
            'nombre' => 'required|string|max:50|unique:equipos,nombre,' . $equipo->id,
            'nombre_proyecto' => 'required|string|max:100',
            'descripcion_proyecto' => 'required|string|max:500',
            'repositorio_url' => 'nullable|url|max:255',
        ]);

        DB::transaction(function () use ($request, $equipo) {
            // 1. Actualizar Equipo
            $equipo->update([
                'nombre' => $request->nombre,
            ]);

            // 2. Actualizar Proyecto
            $equipo->proyecto()->update([
                'nombre' => $request->nombre_proyecto,
                'descripcion' => $request->descripcion_proyecto,
                'repositorio_url' => $request->repositorio_url,
            ]);
        });

        return back()->with('success', 'La información del equipo y proyecto ha sido actualizada.');
    }

    // Agrega un miembro al equipo
    public function addMember(Request $request)
    {
        $equipo = Auth::user()->participante->equipos->first();

        // 1. Validar Capacidad (Máximo 5)
        if ($equipo->participantes()->count() >= 5) {
            return back()->with('error', 'El equipo ya ha alcanzado el máximo de 5 integrantes.');
        }

        $request->validate([
            'participante_id' => 'required|exists:participantes,id',
            'perfil_id' => 'required|exists:perfiles,id',
        ]);

        // 2. Validar que el candidato no tenga equipo (doble check backend)
        $candidato = \App\Models\Participante::find($request->participante_id);
        if ($candidato->equipos()->exists()) {
            return back()->with('error', 'El alumno seleccionado ya tiene equipo.');
        }

        // 3. Agregar
        $equipo->participantes()->attach($request->participante_id, [
            'perfil_id' => $request->perfil_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Participante agregado correctamente.');
    }

    // Eliminar miembro (Lógica inversa)
    public function removeMember($participanteId)
    {
        $equipo = Auth::user()->participante->equipos->first();

        // No permitir eliminarse a sí mismo si es el único líder (opcional)
        if (Auth::user()->participante->id == $participanteId) {
            return back()->with('error', 'No puedes eliminarte a ti mismo desde aquí. Debes salir del equipo o borrarlo.');
        }

        $equipo->participantes()->detach($participanteId);
        return back()->with('success', 'Miembro eliminado.');
    }
/**
     * Permite al usuario autenticado salir de su equipo actual.
     */
    public function leave(Request $request)
    {
        $user = Auth::user();
        $participante = $user->participante;

        // Verificar si tiene equipo
        if (!$participante->equipos()->exists()) {
            return back()->with('error', 'No perteneces a ningún equipo.');
        }

        $equipo = $participante->equipos->first();

        // --- CORRECCIÓN: Búsqueda dinámica del ID de Líder ---
        // Buscamos el perfil por su nombre exacto en la BD
        $perfilLider = \App\Models\Perfil::where('nombre', 'Líder de Proyecto')->first();
        
        // Fallback de seguridad por si no existe el perfil en BD (evita crash)
        $idLider = $perfilLider ? $perfilLider->id : null; 

        // Verificamos si el usuario actual tiene ese rol en la tabla pivote
        $esLider = $equipo->participantes()
            ->where('participante_id', $participante->id)
            ->wherePivot('perfil_id', $idLider) 
            ->exists();

        if ($esLider) {
            // Regla de Negocio: El líder no puede abandonar, debe ceder el rol o eliminar el equipo.
            return back()->with('error', 'Como líder, no puedes abandonar el equipo. Debes eliminar el proyecto completo o asignar otro líder antes de salir.');
        }

        // Desvincular al participante
        $equipo->participantes()->detach($participante->id);

        return redirect()->route('participante.dashboard')->with('success', 'Has abandonado el equipo correctamente.');
    }
}
