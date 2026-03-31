<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends Model
{
    use HasFactory;

    // Nombre real de la tabla
    protected $table = 'd_area';

    // Primary key personalizada
    protected $primaryKey = 'id_area';

    // Tipo de PK (por si acaso, SQL Server)
    protected $keyType = 'int';

    // Auto incremental
    public $incrementing = true;

    // Timestamps activados (created_at, updated_at)
    public $timestamps = true;

    // Campos asignables masivamente
    protected $fillable = [
        'nombre',
        'status_id',
        'created_by',
        'updated_by',
    ];

    // Relación: un área tiene muchos usuarios
    public function users()
    {
        return $this->hasMany(User::class, 'id_area', 'id_area');
    }

    // Relación: área pertenece a un status
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id_status');
    }

    // Usuario que creó el registro
    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // Usuario que actualizó
    public function actualizador()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
     public function departamentos()
    {
        return $this->hasMany(Departamento::class, 'area_id', 'id_area');
    }
}