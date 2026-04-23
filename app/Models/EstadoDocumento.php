<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoDocumento extends Model
{
    protected $table = 'estado_documento';
    protected $primaryKey = 'id_estado';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    public function evidencias()
    {
        return $this->hasMany(Evidencia::class, 'estado_actual', 'id_estado');
    }

    public function versiones()
    {
        return $this->hasMany(VersionDocumento::class, 'id_estado', 'id_estado');
    }

    public function flujosPasos()
    {
        return $this->hasMany(FlujoPaso::class, 'estado_salida', 'id_estado');
    }

    public function flujoEjecuciones()
    {
        return $this->hasMany(FlujoEjecucion::class, 'estado_actual', 'id_estado');
    }
}