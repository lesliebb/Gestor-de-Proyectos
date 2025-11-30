<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsuarioRequest;
use App\Http\Requests\Admin\UpdateUsuarioRequest;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsuariosExport;


class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('roles')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $roleName = $request->role;
            $query->whereHas('roles', function ($q) use ($roleName) {
                $q->where('nombre', $roleName);
            });
        }

        $usuarios = $query->paginate(10)->withQueryString();

        $roles = Rol::all();

        return view('admin.usuarios.index', compact('usuarios', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Enviamos los roles a la vista para el <select>
        $roles = Rol::all();
        return view('admin.usuarios.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     * 
     * Crear el usuario (encriptando la contraseña).
     */
    public function store(StoreUsuarioRequest $request)
    {
        $user = User::create([
            'name' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->roles()->attach($request->rol_id);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado y rol asignado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource.
     * 
     * Reutilizamos la vista de edit o podrías hacer una show aparte.
     * Por ahora redirigimos a edit para agilizar.
     */
    public function show(User $usuario)
    {
        return redirect()->route('admin.usuarios.edit', $usuario);
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
     * Show the form for editing the specified resource.
     * 
     * Cargamos los roles actuales del usuario para marcarlos en el select.
     */
    public function edit(User $usuario)
    {
        $roles = Rol::all();
        $usuario->load('roles');

        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     * 
     * Preparar datos básicos.
     * Si escribió contraseña nueva, la encriptamos. Si no, la ignoramos.
     * Actualizar tabla users.
     * Sincronizar Rol (Borra los anteriores y pone el nuevo).
     */
    public function update(UpdateUsuarioRequest $request, User $usuario)
    {
        $data = [
            'name' => $request->nombre,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        $usuario->roles()->sync([$request->rol_id]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     * 
     * Evitar que el admin se borre a sí mismo por error.
     */
    public function destroy(User $usuario)
    {
        if (Auth::id() === $usuario->id) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();
        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado del sistema.');
    }

    /**
     * Exportar usuarios a Excel con filtros aplicados
     */
    public function exportar(Request $request)
    {
        return Excel::download(
            new UsuariosExport($request->all()), 
            'usuarios_' . date('Y-m-d_His') . '.xlsx'
        );
    }
}
