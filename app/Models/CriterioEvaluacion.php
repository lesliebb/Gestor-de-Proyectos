<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class CriterioEvaluacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'criterio_evaluacion';

    protected $fillable = ['evento_id', 'nombre', 'ponderacion'];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'criterio_id');
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
