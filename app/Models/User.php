<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'id_area',
        'id_departamento',
        'id_rol',
        'first_surname',
        'second_last_name',
        'id_status',
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
    //relaciones
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area', 'id_area');
    }
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento', 'id_departamento');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id_status');
    }

    public function hasRole(string $roleName): bool
    {
        // Carga la relación solo si no está ya cargada (evita N+1)
        $rol = $this->relationLoaded('rol') ? $this->rol : $this->rol()->first();

        if (!$rol) {
            return false;
        }

        // Comparación insensible a mayúsculas y espacios
        return strtolower(trim($rol->name)) === strtolower(trim($roleName));
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Administrador');
    }

    public function isDirector(): bool
    {
        return $this->hasRole('Director');
    }

    public function isLiderCaracteristica(): bool
    {
        return $this->hasRole('LiderCaracteristica');
    }

    public function isEnlace(): bool
    {
        return $this->hasRole('Enlace');
    }

    public function getRolNombre(): string
    {
        $rol = $this->relationLoaded('rol') ? $this->rol : $this->rol()->first();
        return $rol ? $rol->nombre : 'Sin rol';
    }
}
