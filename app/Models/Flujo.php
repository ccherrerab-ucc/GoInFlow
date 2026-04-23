<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flujo extends Model
{
    protected $table = 'flujo';
    protected $primaryKey = 'id_flujo';
    public $timestamps = true;

    protected $fillable = ['nombre'];

    public function pasos()
    {
        return $this->hasMany(FlujoPaso::class, 'id_flujo', 'id_flujo');
    }

    public function ejecuciones()
    {
        return $this->hasMany(FlujoEjecucion::class, 'id_flujo', 'id_flujo');
    }
}