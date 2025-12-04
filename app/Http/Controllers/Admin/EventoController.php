<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEventoRequest;
use App\Http\Requests\Admin\UpdateEventoRequest;
use App\Mail\JuezEventoAsignado;
use App\Models\Evento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eventos = Evento::latest()->paginate(10);
        return view('admin.eventos.index', compact('eventos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jueces = User::whereHas('roles', fn($q) => $q->where('nombre', 'Juez'))->get();
        return view('admin.eventos.create', compact('jueces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventoRequest $request)
    {
        $evento = Evento::create($request->validated());
        $juecesIds = $request->input('jueces', []);
        $evento->jueces()->attach($juecesIds);
        
        // Enviar email a cada juez asignado
        $jueces = User::whereIn('id', $juecesIds)->get();
        foreach ($jueces as $juez) {
            Mail::to($juez->email)->send(new JuezEventoAsignado($juez, $evento));
        }
        
        return redirect()->route('admin.eventos.index')->with('success', 'Evento creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Evento $evento)
    {
        return view('admin.eventos.show', compact('evento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evento $evento)
    {
        $jueces = User::whereHas('roles', fn($q) => $q->where('nombre', 'Juez'))->get();
        return view('admin.eventos.edit', compact('evento', 'jueces'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventoRequest $request, Evento $evento)
    {
        $evento->update($request->validated());
        $nuevosjuecesIds = $request->input('jueces', []);
        $juecesActuales = $evento->jueces->pluck('id')->toArray();
        
        // Obtener los jueces nuevos (los que no estaban antes)
        $juecesNuevos = array_diff($nuevosjuecesIds, $juecesActuales);
        
        $evento->jueces()->sync($nuevosjuecesIds);
        
        // Enviar email solo a los jueces nuevamente asignados
        if (!empty($juecesNuevos)) {
            $jueces = User::whereIn('id', $juecesNuevos)->get();
            foreach ($jueces as $juez) {
                Mail::to($juez->email)->send(new JuezEventoAsignado($juez, $evento));
            }
        }
        
        return redirect()->route('admin.eventos.index')->with('success', 'Evento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     * 
     * Logica de negocio para eliminar un evento: si ya se inicio no se puede eliminar.
     */
    public function destroy(Evento $evento)
    {
        $evento->delete();
        return redirect()->route('admin.eventos.index')->with('success', 'Evento eliminado exitosamente.');
    }
}
