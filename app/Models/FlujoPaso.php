<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlujoPaso extends Model
{
    protected $table = 'flujo_paso';
    protected $primaryKey = 'id_paso';
    public $timestamps = true;

    protected $fillable = [
        'id_flujo', 'orden', 'rol_requerido', 'cantidad_aprobadores', 'estado_salida'
    ];

    public function flujo()
    {
        return $this->belongsTo(Flujo::class, 'id_flujo', 'id_flujo');
    }

    public function estadoSalida()
    {
        return $this->belongsTo(EstadoDocumento::class, 'estado_salida', 'id_estado');
    }

    public function rolRequerido()
    {
        return $this->belongsTo(Rol::class, 'rol_requerido', 'id_rol');
    }
}