<?php

namespace App\Http\Controllers\Participante;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Participante;
use App\Models\InvitacionEquipo;
use Illuminate\Http\Request;

class InvitacionEquipoController extends Controller
{
    /**
     * Líder invita a un participante
     * GET /participante/equipos/{equipo}/invitar
     */
    public function showInvitarForm(Request $request, Equipo $equipo)
    {
        $participante = $request->user()->participante;
        $lider = $equipo->getLider();

        // Verificar permisos
        if (!$lider || $lider->id !== $participante->id) {
            return back()->with('error', 'Solo el líder puede enviar invitaciones.');
        }

        // Obtener participantes sin equipo
        $participantesSinEquipo = Participante::whereDoesntHave('equipos')
            ->with('user', 'carrera')
            ->get();

        $rolesDisponibles = $equipo->getRolesDisponibles();

        return view('participante.invitaciones.enviar', compact(
            'equipo', 'participantesSinEquipo', 'rolesDisponibles'
        ));
    }

    /**
     * Guardar invitación
     * POST /participante/equipos/{equipo}/invitar
     */
    public function enviarInvitacion(Request $request, Equipo $equipo)
    {
        $request->validate([
            'participante_id' => 'required|exists:participantes,id',
            'perfil_sugerido_id' => 'nullable|exists:perfiles,id',
            'mensaje' => 'nullable|string|max:500',
        ]);

        $lider = $request->user()->participante;

        // Verificar que sea líder
        if ($equipo->getLider()->id !== $lider->id) {
            return back()->with('error', 'No tienes permisos.');
        }

        // Verificar que el equipo tenga espacio
        if ($equipo->estaCompleto()) {
            return back()->with('error', 'El equipo está completo.');
        }

        // Verificar que el participante exista y no esté en otro equipo
        $participante = Participante::findOrFail($request->participante_id);
        if ($participante->equipos->isNotEmpty()) {
            return back()->with('error', 'Este participante ya está en un equipo.');
        }

        // Verificar que no exista invitación previa PENDIENTE
        $invitacionExistente = InvitacionEquipo::where('equipo_id', $equipo->id)
            ->where('participante_id', $participante->id)
            ->where('estado', 'pendiente')
            ->first();

        if ($invitacionExistente) {
            return back()->with('error', 'Ya existe una invitación pendiente para este participante.');
        }

        try {
            // Crear invitación
            $invitacion = InvitacionEquipo::create([
                'equipo_id' => $equipo->id,
                'participante_id' => $participante->id,
                'perfil_sugerido_id' => $request->perfil_sugerido_id,
                'mensaje' => $request->mensaje,
                'estado' => 'pendiente',
                'enviada_por_participante_id' => $lider->id,
            ]);

            // Disparar evento
            event(new \App\Events\InvitacionEquipoEnviada($invitacion));

            return redirect()->route('participante.equipos.edit', $equipo)
                ->with('success', 'Invitación enviada correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23505' || strpos($e->getMessage(), 'unique') !== false) {
                return back()->with('error', 'Ya existe una invitación para este participante.');
            }
            throw $e;
        }
    }

    /**
     * Ver invitaciones pendientes del participante
     * GET /participante/invitaciones
     */
    public function misInvitaciones(Request $request)
    {
        $participante = $request->user()->participante;
        
        $invitaciones = $participante->invitaciones()
            ->with('equipo.proyecto', 'enviadaPor.user', 'perfilSugerido')
            ->latest()
            ->paginate(10);

        return view('participante.invitaciones.mis-invitaciones', compact('invitaciones'));
    }

    /**
     * Participante ACEPTA invitación
     * POST /participante/invitaciones/{invitacion}/aceptar
     */
    public function aceptar(Request $request, InvitacionEquipo $invitacion)
    {
        $participante = $request->user()->participante;

        // Verificar permisos
        if ($invitacion->participante_id !== $participante->id) {
            return back()->with('error', 'No tienes permisos.');
        }

        // Verificar que sea pendiente
        if ($invitacion->estado !== 'pendiente') {
            return back()->with('error', 'Esta invitación ya ha sido respondida.');
        }

        // Verificar que el equipo aún tenga espacio
        if ($invitacion->equipo->estaCompleto()) {
            return back()->with('error', 'El equipo ya está completo.');
        }

        // Verificar que el rol tenga vacantes
        if ($invitacion->perfil_sugerido_id && !$invitacion->equipo->tieneVacantesParaRol($invitacion->perfil_sugerido_id)) {
            return back()->with('error', 'El rol sugerido ya no tiene vacantes.');
        }

        // Aceptar invitación
        $invitacion->update([
            'estado' => 'aceptada',
            'respondida_en' => now()
        ]);

        // Agregar al equipo
        $perfilId = $invitacion->perfil_sugerido_id ?? 1; // Default: Programador
        $invitacion->equipo->participantes()->attach(
            $participante->id,
            ['perfil_id' => $perfilId]
        );

        // Rechazar otras invitaciones pendientes de este participante
        InvitacionEquipo::where('participante_id', $participante->id)
            ->where('estado', 'pendiente')
            ->where('id', '!=', $invitacion->id)
            ->update(['estado' => 'rechazada', 'respondida_en' => now()]);

        event(new \App\Events\InvitacionEquipoAceptada($invitacion));

        return back()->with('success', 'Has aceptado la invitación al equipo.');
    }

    /**
     * Participante RECHAZA invitación
     * POST /participante/invitaciones/{invitacion}/rechazar
     */
    public function rechazar(Request $request, InvitacionEquipo $invitacion)
    {
        $participante = $request->user()->participante;

        // Verificar permisos
        if ($invitacion->participante_id !== $participante->id) {
            return back()->with('error', 'No tienes permisos.');
        }

        // Verificar que sea pendiente
        if ($invitacion->estado !== 'pendiente') {
            return back()->with('error', 'Esta invitación ya ha sido respondida.');
        }

        // Rechazar
        $invitacion->update([
            'estado' => 'rechazada',
            'respondida_en' => now()
        ]);

        event(new \App\Events\InvitacionEquipoRechazada($invitacion));

        return back()->with('success', 'Invitación rechazada.');
    }

    /**
     * Líder ve invitaciones enviadas
     * GET /participante/equipos/{equipo}/invitaciones/enviadas
     */
    public function invitacionesEnviadas(Request $request, Equipo $equipo)
    {
        $participante = $request->user()->participante;
        $lider = $equipo->getLider();

        if (!$lider || $lider->id !== $participante->id) {
            return back()->with('error', 'Solo el líder puede ver esto.');
        }

        $invitaciones = $equipo->invitaciones()
            ->with('participante.user', 'perfilSugerido')
            ->latest()
            ->paginate(10);

        return view('participante.invitaciones.enviadas', compact('equipo', 'invitaciones'));
    }
}

