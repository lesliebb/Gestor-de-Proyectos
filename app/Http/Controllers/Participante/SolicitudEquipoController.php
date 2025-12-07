<?php

namespace App\Http\Controllers\Participante;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\SolicitudEquipo;
use App\Events\SolicitudEquipoEnviada;
use App\Events\SolicitudEquipoAceptada;
use App\Events\SolicitudEquipoRechazada;
use Illuminate\Http\Request;

class SolicitudEquipoController extends Controller
{
    public function showCrearSolicitud(Request $request, Equipo $equipo)
    {
        $participante = $request->user()->participante;
        $rolesDisponibles = $equipo->getRolesDisponibles();

        return view('participante.solicitudes.crear-solicitud', compact('equipo', 'participante', 'rolesDisponibles'));
    }

    public function crearSolicitud(Request $request, Equipo $equipo)
    {
        $request->validate([
            'perfil_solicitado_id' => 'required|exists:perfiles,id',
            'mensaje' => 'nullable|string|max:500',
        ]);

        $participante = $request->user()->participante;

        // Validar que el evento aún no ha comenzado
        $evento = $equipo->proyecto->evento;
        if ($evento->fecha_inicio <= now()) {
            return redirect()->route('participante.dashboard')->with('error', 'Este evento ya ha comenzado. No se pueden aceptar nuevas solicitudes.');
        }

        // Validar que no esté en el equipo
        if ($participante->equipos->contains($equipo->id)) {
            return redirect()->route('participante.dashboard')->with('error', 'Ya estás en este equipo.');
        }

        // Validar que no esté en otro equipo
        if ($participante->equipos->isNotEmpty()) {
            return redirect()->route('participante.dashboard')->with('error', 'Ya estás en otro equipo. Debes salirte primero.');
        }

        // Validar que no haya solicitud PENDIENTE (solo pendiente, no aceptada/rechazada)
        $solicitudPendiente = SolicitudEquipo::where('equipo_id', $equipo->id)
            ->where('participante_id', $participante->id)
            ->where('estado', 'pendiente')
            ->first();

        if ($solicitudPendiente) {
            return redirect()->route('participante.dashboard')->with('error', 'Ya tienes una solicitud pendiente para este equipo. Espera a que el líder responda.');
        }

        // Validar que el rol tenga vacantes
        if (!$equipo->tieneVacantesParaRol($request->perfil_solicitado_id)) {
            return redirect()->route('participante.solicitudes.crear.form', $equipo)
                ->with('error', 'El rol seleccionado ya no tiene vacantes disponibles.');
        }

        try {
            // Crear solicitud
            $solicitud = SolicitudEquipo::create([
                'equipo_id' => $equipo->id,
                'participante_id' => $participante->id,
                'perfil_solicitado_id' => $request->perfil_solicitado_id,
                'mensaje' => $request->mensaje,
                'estado' => 'pendiente'
            ]);

            // Disparar evento
            event(new SolicitudEquipoEnviada($solicitud));

            return redirect()->route('participante.dashboard')->with('success', 'Solicitud enviada al líder del equipo.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Captura error de constraint violation (race condition)
            if ($e->getCode() == '23505' || strpos($e->getMessage(), 'unique') !== false) {
                return redirect()->route('participante.dashboard')->with('error', 'Ya existe una solicitud para este equipo. Por favor, espera a que el líder responda.');
            }
            throw $e;
        }
    }

    public function misSolicitudes(Request $request)
    {
        $participante = $request->user()->participante;
        
        $solicitudes = $participante->solicitudes()
            ->with('equipo', 'respondidaPor.user')
            ->latest()
            ->paginate(10);

        return view('participante.solicitudes.mis-solicitudes', compact('solicitudes'));
    }

    public function verSolicitudesEquipo(Request $request, Equipo $equipo)
    {
        $participante = $request->user()->participante;
        $lider = $equipo->getLider();

        // Verificar que sea líder
        if (!$lider || $lider->id !== $participante->id) {
            return back()->with('error', 'No tienes permisos para ver estas solicitudes.');
        }

        $solicitudes = $equipo->solicitudesPendientes()
            ->with(['participante.user', 'participante.carrera', 'perfilSugerido'])
            ->latest()
            ->paginate(10);

        return view('participante.solicitudes.equipo-solicitudes', compact('equipo', 'solicitudes'));
    }

    public function aceptar(Request $request, SolicitudEquipo $solicitud)
    {
        $request->validate([
            'perfil_id' => 'nullable|exists:perfiles,id',
        ]);

        $lider = $request->user()->participante;

        // Verificar que sea el líder del equipo
        if ($solicitud->equipo->getLider()->id !== $lider->id) {
            return back()->with('error', 'No tienes permisos para aceptar esta solicitud.');
        }

        // Verificar que sea pendiente
        if ($solicitud->estado !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya ha sido respondida.');
        }

        // Verificar que el equipo aún tenga espacio
        if ($solicitud->equipo->estaCompleto()) {
            return back()->with('error', 'El equipo ya está completo.');
        }

        // Determinar el rol a asignar
        $perfilId = $request->perfil_id ?? $solicitud->perfil_solicitado_id ?? 1;

        // Verificar que el rol tenga vacantes
        if (!$solicitud->equipo->tieneVacantesParaRol($perfilId)) {
            return back()->with('error', 'El rol seleccionado ya no tiene vacantes.');
        }

        // Aceptar solicitud
        $solicitud->update([
            'estado' => 'aceptada',
            'respondida_por_participante_id' => $lider->id,
            'respondida_en' => now()
        ]);

        // Agregar al equipo con el rol especificado
        $solicitud->equipo->participantes()->attach(
            $solicitud->participante_id,
            ['perfil_id' => $perfilId]
        );

        // AUTOMÁTICAMENTE: Rechazar todas las otras solicitudes pendientes de este participante
        SolicitudEquipo::where('participante_id', $solicitud->participante_id)
            ->where('estado', 'pendiente')
            ->where('id', '!=', $solicitud->id)
            ->update([
                'estado' => 'rechazada',
                'respondida_por_participante_id' => $lider->id,
                'respondida_en' => now()
            ]);

        // Disparar evento
        event(new SolicitudEquipoAceptada($solicitud));

        return back()->with('success', 'Solicitud aceptada. El participante ha sido agregado al equipo.');
    }

    public function rechazar(Request $request, SolicitudEquipo $solicitud)
    {
        $request->validate([
            'razon' => 'nullable|string|max:500',
        ]);

        $lider = $request->user()->participante;

        // Verificar que sea el líder del equipo
        if ($solicitud->equipo->getLider()->id !== $lider->id) {
            return back()->with('error', 'No tienes permisos para rechazar esta solicitud.');
        }

        // Verificar que sea pendiente
        if ($solicitud->estado !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya ha sido respondida.');
        }

        // Rechazar solicitud
        $solicitud->update([
            'estado' => 'rechazada',
            'respondida_por_participante_id' => $lider->id,
            'respondida_en' => now()
        ]);

        // Disparar evento
        event(new SolicitudEquipoRechazada($solicitud));

        return back()->with('success', 'Solicitud rechazada.');
    }
}

