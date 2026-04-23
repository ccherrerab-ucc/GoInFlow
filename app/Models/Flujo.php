<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flujo extends Model
{
    protected $table = 'flujo';
    protected $primaryKey = 'id_flujo';
    public $timestamps = true;

    protected $fillable = ['nombre', 'id_aspecto', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function aspecto()
    {
        return $this->belongsTo(Aspecto::class, 'id_aspecto', 'id_aspecto');
    }

    public function pasos()
    {
        return $this->hasMany(FlujoPaso::class, 'id_flujo', 'id_flujo')->orderBy('orden');
    }

    public function ejecuciones()
    {
        return $this->hasMany(FlujoEjecucion::class, 'id_flujo', 'id_flujo');
    }
}