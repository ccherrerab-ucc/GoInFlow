<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    public $timestamps = true;
    protected $table = 'rol';
    protected $primaryKey = 'id_rol';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'status_id',
        'created_by',
        'updated_by',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_rol', 'id_rol');
    }
    // Relación: rol pertenece a un status
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
}
