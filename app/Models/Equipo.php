<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class Equipo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipos';

    protected $fillable = ['nombre', 'max_programadores', 'max_disenadores', 'max_testers'];

    public function proyecto()
    {
        return $this->hasOne(Proyecto::class);
    }

    public function participantes()
    {
        return $this->belongsToMany(Participante::class, 'equipo_participante')->withPivot('perfil_id');
    }

    public function solicitudes()
    {
        return $this->hasMany(SolicitudEquipo::class);
    }

    public function solicitudesPendientes()
    {
        return $this->solicitudes()->where('estado', 'pendiente');
    }

    public function getLider()
    {
        return $this->participantes()
            ->wherePivot('perfil_id', 3)
            ->first();
    }
    public function removerIntegrante($participanteId)
    {
        $idPerfilLider = 3;

        // 1. Verificar si el que se va TIENE EL PERFIL DE LÍDER
        $esLider = $this->participantes()
            ->wherePivot('participante_id', $participanteId)
            ->wherePivot('perfil_id', $idPerfilLider)
            ->exists();

        // 2. Eliminar al usuario
        $this->participantes()->detach($participanteId);

        // 3. Lógica de Sucesión
        if ($esLider) {
            // Buscamos al miembro más antiguo que queda
            $nuevoLider = $this->participantes()
                ->orderBy('equipo_participante.created_at', 'asc')
                ->first();

            if ($nuevoLider) {
                // Le asignamos el PERFIL DE LÍDER (ID 3)
                $this->participantes()->updateExistingPivot($nuevoLider->id, ['perfil_id' => $idPerfilLider]);
            }
        }
    }

    public function getConteoRoles()
    {
        return [
            'programadores' => $this->participantes()->wherePivot('perfil_id', 1)->count(),
            'disenadores' => $this->participantes()->wherePivot('perfil_id', 2)->count(),
            'testers' => $this->participantes()->wherePivot('perfil_id', 4)->count(),
            'lider' => $this->participantes()->wherePivot('perfil_id', 3)->count(),
        ];
    }

    public function tieneVacantesParaRol($perfilId)
    {
        $conteo = $this->getConteoRoles();
        
        switch ($perfilId) {
            case 1: // Programador
                return $conteo['programadores'] < $this->max_programadores;
            case 2: // Diseñador
                return $conteo['disenadores'] < $this->max_disenadores;
            case 4: // Tester
                return $conteo['testers'] < $this->max_testers;
            default:
                return false;
        }
    }

    public function getRolesDisponibles()
    {
        $roles = [];
        $conteo = $this->getConteoRoles();
        
        // Programador (ID 1)
        $programadoresDisponibles = max(0, $this->max_programadores - $conteo['programadores']);
        if ($programadoresDisponibles > 0) {
            $roles[] = [
                'id' => 1,
                'nombre' => 'Programador',
                'disponibles' => $programadoresDisponibles,
                'total' => $this->max_programadores
            ];
        }
        
        // Diseñador (ID 2)
        $disenadoresDisponibles = max(0, $this->max_disenadores - $conteo['disenadores']);
        if ($disenadoresDisponibles > 0) {
            $roles[] = [
                'id' => 2,
                'nombre' => 'Diseñador',
                'disponibles' => $disenadoresDisponibles,
                'total' => $this->max_disenadores
            ];
        }
        
        // Tester (ID 4)
        $testersDisponibles = max(0, $this->max_testers - $conteo['testers']);
        if ($testersDisponibles > 0) {
            $roles[] = [
                'id' => 4,
                'nombre' => 'Tester',
                'disponibles' => $testersDisponibles,
                'total' => $this->max_testers
            ];
        }
        
        return $roles;
    }

    public function estaCompleto()
    {
        return $this->participantes()->count() >= 5;
    }
    public function getRouteKey()
    {
        return Hashids::encode($this->getKey());
    }
    public function resolveRouteBinding($value, $field = null)
    {
        $decoded = Hashids::decode($value);
        if (empty($decoded)) {
            return null;
        }
        $realId = $decoded[0];
        return $this->where('id', $realId)->firstOrFail();
    }
}
