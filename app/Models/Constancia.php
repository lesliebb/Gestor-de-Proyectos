<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class Constancia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'constancias';

    protected $fillable = ['participante_id', 'evento_id', 'tipo', 'archivo_path', 'codigo_qr'];

    public function participante()
    {
        return $this->belongsTo(Participante::class);
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class);
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
