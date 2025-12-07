<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class Participante extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'participantes';

    protected $fillable = ['user_id', 'carrera_id', 'no_control', 'telefono'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }

    public function equipos()
    {
        // Relación Muchos a Muchos con Equipos
        // Especificamos la tabla pivote 'equipo_participante' para evitar errores de nombres
        return $this->belongsToMany(Equipo::class, 'equipo_participante', 'participante_id', 'equipo_id')
            ->withPivot('perfil_id') // Para poder acceder al rol dentro del equipo
            ->withTimestamps();
    }

    public function solicitudes()
    {
        return $this->hasMany(SolicitudEquipo::class);
    }

    public function solicitudesRecibidas()
    {
        return $this->hasMany(SolicitudEquipo::class, 'respondida_por_participante_id');
    }

    public function esLiderDe()
    {
        return $this->equipos()
            ->wherePivot('perfil_id', 3)
            ->first();
    }

    public function constancias()
    {
        return $this->hasMany(Constancia::class);
    }
    public function participante()
    {
        // Un Usuario tiene un perfil de Participante
        return $this->hasOne(Participante::class);
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
