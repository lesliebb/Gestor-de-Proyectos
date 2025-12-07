<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class Calificacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'calificaciones';

    protected $fillable = ['proyecto_id', 'juez_user_id', 'criterio_id', 'puntuacion'];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function juez()
    {
        return $this->belongsTo(User::class, 'juez_user_id');
    }

    public function criterio()
    {
        return $this->belongsTo(CriterioEvaluacion::class, 'criterio_id');
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
