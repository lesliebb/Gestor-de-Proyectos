<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEquipoRequest;
use App\Http\Requests\Admin\UpdateEquipoRequest;
use App\Models\Equipo;
use App\Models\Evento;
use App\Models\Participante;
use App\Models\Perfil;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipo::with(['proyecto.evento', 'participantes']);
        if ($request->filled('search'))
            $query->where('nombre', 'like', '%' . $request->search . '%');

        $equipos = $query->latest()->paginate(9)->withQueryString();
        $eventos = Evento::all();

        return view('admin.equipos.index', compact('equipos', 'eventos'));
    }

    public function create()
    {
        $eventos = Evento::where('fecha_inicio', '>', now())->get();
        return view('admin.equipos.create', compact('eventos'));
    }

    public function store(StoreEquipoRequest $request)
    {
        $equipo = Equipo::create($request->validated());


        return redirect()->route('admin.equipos.show', $equipo)
            ->with('success', 'Equipo creado. Ahora agrega los integrantes.');
    }

    public function show(Equipo $equipo)
    {

        $equipo->load(['participantes.user', 'participantes.carrera', 'proyecto.evento']);

        $perfiles = Perfil::all();

        $todos_participantes = Participante::with('user')->get();

        return view('admin.equipos.show', compact('equipo', 'perfiles', 'todos_participantes'));
    }

    public function edit(Equipo $equipo)
    {
        $eventos = Evento::all();
        return view('admin.equipos.edit', compact('equipo', 'eventos'));
    }

    public function update(UpdateEquipoRequest $request, Equipo $equipo)
    {
        $equipo->update($request->validated());
        return redirect()->route('admin.equipos.show', $equipo)->with('success', 'Equipo actualizado.');
    }

    public function destroy(Equipo $equipo)
    {
        $equipo->delete();
        return redirect()->route('admin.equipos.index')->with('success', 'Equipo eliminado.');
    }


    /**
     * Agrega un miembro a un equipo.
     * 
     * VALIDACIÓN LÍDER: ¿Ya hay un líder y están intentando meter otro?
     * AGREGAR ALUMNO.
     */
    public function addMember(Request $request, Equipo $equipo)
    {
        $request->validate([
            'participante_id' => 'required|exists:participantes,id',
            'perfil_id' => 'required|exists:perfiles,id',
        ]);

        $participante = Participante::find($request->participante_id);

        if ($participante->equipos()->exists()) {
            return back()->with('error', 'El participante ya pertenece a un equipo. Debe salirse primero.');
        }

        $perfilLider = Perfil::where('nombre', 'Líder de Proyecto')->first();
        if ($perfilLider && $request->perfil_id == $perfilLider->id) {
            $yaHayLider = $equipo->participantes()
                ->wherePivot('perfil_id', $perfilLider->id)
                ->exists();

            if ($yaHayLider) {
                return back()->with('error', 'Este equipo ya tiene un Líder asignado.');
            }
        }

        $equipo->participantes()->attach($request->participante_id, [
            'perfil_id' => $request->perfil_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Participante agregado correctamente.');
    }

    public function removeMember(Equipo $equipo, Participante $participante)
    {

        $equipo->removerIntegrante($participante->id);

        return back()->with('success', 'Miembro eliminado. Si era líder, el rol fue reasignado.');
    }
}