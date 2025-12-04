<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Participante;
use App\Notifications\CustomResetPasswordNotification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function participante(): HasOne
    {
        return $this->hasOne(Participante::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'user_rol', 'user_id', 'rol_id');
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'juez_user_id');
    }
    public function getDashboardRouteName()
    {
        if ($this->roles->contains('nombre', 'Admin')) {
            return 'admin.dashboard';
        }

        if ($this->roles->contains('nombre', 'Juez')) {
            return 'juez.dashboard';
        }

        if ($this->roles->contains('nombre', 'Participante')) {
            return 'participante.dashboard';
        }

        return 'login'; // Fallback por si acaso
    }

    public function hasRole($roleName)
    {
        return $this->roles->contains('nombre', $roleName);
    }

    public function eventosAsignados()
    {
        return $this->belongsToMany(Evento::class, 'evento_user', 'user_id', 'evento_id');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
